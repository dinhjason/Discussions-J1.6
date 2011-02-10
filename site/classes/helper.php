<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access');

require_once(JPATH_COMPONENT.DS.'classes/user.php');


/**
 * Helper class
 *
 */
class CofiHelper extends JObject {


    public $getC;

    function replace_emoticon_tags( $text) {

		// website root directory
		$_root = JURI::root();

		$img_cool = "<img src='" . $_root . "components/com_discussions/assets/emoticons/cool.gif' />";
		$text = str_replace( "8-)", $img_cool, $text);

		$img_crying = "<img src='" . $_root . "components/com_discussions/assets/emoticons/crying.gif' />";
		$text = str_replace( ":'(", $img_crying, $text);

		$img_innocent = "<img src='" . $_root . "components/com_discussions/assets/emoticons/innocent.gif' />";
		$text = str_replace( "O:-)", $img_innocent, $text);

		$img_devil = "<img src='" . $_root . "components/com_discussions/assets/emoticons/devil.gif' />";
		$text = str_replace( ">:-)", $img_devil, $text);

		$img_laugh = "<img src='" . $_root . "components/com_discussions/assets/emoticons/laugh.gif' />";
		$text = str_replace( ":-D", $img_laugh, $text);

		$img_kiss = "<img src='" . $_root . "components/com_discussions/assets/emoticons/kiss.gif' />";
		$text = str_replace( ":-*", $img_kiss, $text);

		$img_sad = "<img src='" . $_root . "components/com_discussions/assets/emoticons/sad.gif' />";
		$text = str_replace( ":-(", $img_sad, $text);

		$img_smile = "<img src='" . $_root . "components/com_discussions/assets/emoticons/smile.gif' />";
		$text = str_replace( ":-)", $img_smile, $text);

		$img_tongue = "<img src='" . $_root . "components/com_discussions/assets/emoticons/tongue.gif' />";
		$text = str_replace( ":-P", $img_tongue, $text);

		$img_thumbup = "<img src='" . $_root . "components/com_discussions/assets/emoticons/thumbup.gif' />";
		$text = str_replace( "(Y)", $img_thumbup, $text);

		$img_thumbdown = "<img src='" . $_root . "components/com_discussions/assets/emoticons/thumbdown.gif' />";
		$text = str_replace( "(N)", $img_thumbdown, $text);

		$img_wink = "<img src='" . $_root . "components/com_discussions/assets/emoticons/wink.gif' />";
		$text = str_replace( ";-)", $img_wink, $text);


  		return $text;
	}



	function replace_bb_tags( $text) {

		$search = array( 
            '/\[b\](.*?)\[\/b\]/is', 
            '/\[i\](.*?)\[\/i\]/is', 
            '/\[u\](.*?)\[\/u\]/is', 
            '/\[s\](.*?)\[\/s\]/is', 
            '/\[big\](.*?)\[\/big\]/is', 
            '/\[small\](.*?)\[\/small\]/is', 
            '/\[ul\](.*?)\[\/ul\]/is', 
            '/\[ol\](.*?)\[\/ol\]/is', 
            '/\[li\](.*?)\[\/li\]/is', 
            '/\[quote\](.*?)\[\/quote\]/is', 
            '/\[url\](.*?)\[\/url\]/is', 
            '/\[url\=(.*?)\](.*?)\[\/url\]/is' 
        ); 

    	$replace = array( 
            '<strong>$1</strong>', 
            '<em>$1</em>', 
            '<u>$1</u>', 
            '<s>$1</s>', 
            '<big>$1</big>', 
            '<small>$1</small>', 
            '<ul>$1</ul>', 
            '<ol>$1</ol>', 
            '<li>$1</li>', 
            '<blockquote>$1</blockquote>', 
            '<a href="$1" target="_blank" rel="nofollow">$1</a>', 
            '<a href="$1" target="_blank" rel="nofollow">$2</a>' 
        ); 

		$text = preg_replace ( $search, $replace, $text); 

  		return $text;
	}


    function replace_flickr_tags( $f, $mode, $posting_id, $text) {

        $count = substr_count  (  $text  ,  "[flickr=");

        $_offset = 0;
        $textnew = $text;


        for ( $i=0; $i < $count; $i++) {


            $_start = strpos  ( $text  ,  "[flickr=", $_offset);
            $_end   = strpos  ( $text  ,  "]", $_start) ;

            $_id = substr( $text, $_start + 8, $_end-$_start-8);

            $_source = substr( $text, $_start, $_end-$_start+1);


            $photo = $f->photos_getInfo( $_id);

            $_small  = $f->buildPhotoURL( $photo, "square");
            $_medium = $f->buildPhotoURL( $photo, "medium");
            $_target = $photo['urls']['url'][0]['_content'];

            $_replacement = "";


            // detect display mode: 0=browser popup, 1=slimbox, 3=YOOeffects
            switch ( $mode) {

                case 1: { // slimbox
                    $_replacement .= "<a rel='lightbox-$posting_id' href='$_medium' title='$photo[title]' target='_blank' >";
                    $_replacement .= "<img width='75px' heigth='75px' border='0' alt='$photo[title]' " . "src='" . $_small . "' class='cofiFlickrImage' >";
                    $_replacement .= "</a>";
                    break;
                }

                case 2: { // RokBox
                    $_replacement .= "<a rel='rokbox ($posting_id)' href='$_medium' title='$photo[title]' target='_blank' >";
                    $_replacement .= "<img width='75px' heigth='75px' border='0' alt='$photo[title]' " . "src='" . $_small . "' class='cofiFlickrImage' >";
                    $_replacement .= "</a>";
                    break;
                }

                case 3: { // ZOOeffects
                    $_replacement .= "<a rel='shadowbox[$posting_id]' href='$_medium' title='$photo[title]' target='_blank' >";
                    $_replacement .= "<img width='75px' heigth='75px' border='0' alt='$photo[title]' " . "src='" . $_small . "' class='cofiFlickrImage' >";
                    $_replacement .= "</a>";
                    break;
                }


                default: { // 0 or nothing above = browser window
                    $_replacement .= "<a href='$_target' title='$photo[title]' target='_blank' >";
                    $_replacement .= "<img width='75px' heigth='75px' border='0' alt='$photo[title]' " . "src='" . $_small . "' class='cofiFlickrImage' >";
                    $_replacement .= "</a>";
                    break;
                }

            }


            $textnew = str_replace( $_source, $_replacement, $textnew);

            $_offset = $_end;

        }

        return $textnew;

    }



    function replace_youtube_tags( $width, $height, $text) {

        $count = substr_count  (  $text  ,  "[youtube=");

        $_offset = 0;
        $textnew = $text;


        for ( $i=0; $i < $count; $i++) {


            $_start = strpos  ( $text  ,  "[youtube=", $_offset);
            $_end   = strpos  ( $text  ,  "]", $_start) ;

            $_id = substr( $text, $_start + 9, $_end-$_start-9);

            $_source = substr( $text, $_start, $_end-$_start+1);



            $_replacement = "";

			$_replacement .= "<object width='" . $width . "' height='" . $height . "'>";
			$_replacement .= "<param name='movie' value='http://www.youtube.com/v/" . $_id . "'></param>";
			$_replacement .= "<param name='allowFullScreen' value='true'></param>";
			$_replacement .= "<param name='allowscriptaccess' value='always'></param>";
			
			$_replacement .= "<embed src='http://www.youtube.com/v/" . $_id . "' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='" . $width . "' height='" . $height ."'></embed>";
			
			$_replacement .= "</object>";
			
			

            $textnew = str_replace( $_source, $_replacement, $textnew);

            $_offset = $_end;

        }

        return $textnew;

    }



	function close_html_tags( $html) {
		preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU",$html,$result);
  		$openedtags=$result[1];

  		preg_match_all("#</([a-z]+)>#iU",$html,$result);
  		$closedtags=$result[1];
  		
  		$len_opened = count($openedtags);

  		if(count($closedtags) == $len_opened){
    		return $html;
  		}

  		$openedtags = array_reverse($openedtags);

		for($i=0;$i < $len_opened;$i++) {
    		if (!in_array($openedtags[$i],$closedtags)){
      			$html .= '</'.$openedtags[$i].'>';
    		} else {
      			unset($closedtags[array_search($openedtags[$i],$closedtags)]);
    		}
  		}
  		return $html;
	}



	function getPostsWFM() {
		$db	=& JFactory::getDBO();		
		$query = "SELECT count(*) FROM #__discussions_messages WHERE wfm=1";
		$db->setQuery($query);
		return $db->loadResult();		
	}



	function increaseUserPostCounter( $user_id) {
		$db	=& JFactory::getDBO();			
	    $sql = "UPDATE ".$db->nameQuote( '#__discussions_users')." SET posts = posts + 1" . 
	     			" WHERE id = '".$user_id."'";	        	
	    $db->setQuery( $sql);
	    return $db->query();
	}

	function decreaseUserPostCounter( $user_id) {
		$db	=& JFactory::getDBO();			
	    $sql = "UPDATE ".$db->nameQuote( '#__discussions_users')." SET posts = posts - 1" . 
	     			" WHERE id = '".$user_id."'";	        	
	    $db->setQuery( $sql);
	    return $db->query();
	}



	function updateThreadStats( $thread_id) {

		$db	=& JFactory::getDBO();			


		// get latest post id
		$sql = "SELECT id FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $thread_id . 
				"' AND published='1' ORDER BY DATE DESC LIMIT 1";
		
		$db->setQuery( $sql);
		$_post_id = $db->loadResult();

	
		// get latest posting date in this thread
		$sql = "SELECT date FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $thread_id . 
				"' AND published='1' ORDER BY DATE DESC LIMIT 1";
		
		$db->setQuery( $sql);
		$_last_entry_date = $db->loadResult();


		// get latest posting user_id in this thread
		$sql = "SELECT user_id FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $thread_id . 
				"' AND published='1' ORDER BY DATE DESC LIMIT 1";
		
		$db->setQuery( $sql);
		$_last_entry_user_id = $db->loadResult();


		// get # of replies in this thread
		$sql = "SELECT count(*) FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $thread_id . 
				"' AND published='1' AND parent_id <> '0'";
		
		$db->setQuery( $sql);
		$_counter_replies = $db->loadResult();


					
		// update thread stats
		$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages') . 
				" SET " .
				"last_entry_date ='".$_last_entry_date."', " .
			 	"last_entry_user_id ='".$_last_entry_user_id."', " . 
			 	"last_entry_msg_id ='".$_post_id."', " . 
			 	"counter_replies ='".$_counter_replies."'" . 
				" WHERE thread = '".$thread_id."' AND parent_id='0'";

		$db->setQuery( $sql);
		
		return $db->query();	
	
	}


	function updateAllThreadStats() {

		$db	=& JFactory::getDBO();			


		// get all threads in db (parent_id == 0)
        $sql = "SELECT id FROM ".$db->nameQuote( '#__discussions_messages') . " WHERE parent_id = '0'";
        $db->setQuery( $sql);
        
        $_thread_list = $db->loadAssocList();
        
		reset( $_thread_list);
		while (list($key, $val) = each( $_thread_list)) {
		
        	$thread_id = $_thread_list[$key]['id'];



			// get latest post id
			$sql = "SELECT id FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $thread_id . 
					"' AND published='1' ORDER BY DATE DESC LIMIT 1";
			
			$db->setQuery( $sql);
			$_post_id = $db->loadResult();
	
		
			// get latest posting date in this thread
			$sql = "SELECT date FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $thread_id . 
					"' AND published='1' ORDER BY DATE DESC LIMIT 1";
			
			$db->setQuery( $sql);
			$_last_entry_date = $db->loadResult();
	
	
			// get latest posting user_id in this thread
			$sql = "SELECT user_id FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $thread_id . 
					"' AND published='1' ORDER BY DATE DESC LIMIT 1";
			
			$db->setQuery( $sql);
			$_last_entry_user_id = $db->loadResult();
	
	
			// get # of replies in this thread
			$sql = "SELECT count(*) FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $thread_id . 
					"' AND published='1' AND parent_id <> '0'";
			
			$db->setQuery( $sql);
			$_counter_replies = $db->loadResult();
	
	
						
			// update thread stats
			$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages') . 
					" SET " .
					"last_entry_date ='".$_last_entry_date."', " .
				 	"last_entry_user_id ='".$_last_entry_user_id."', " . 
				 	"last_entry_msg_id ='".$_post_id."', " . 
				 	"counter_replies ='".$_counter_replies."'" . 
					" WHERE thread = '".$thread_id."' AND parent_id='0'";
	
			$db->setQuery( $sql);
			
			$result = $db->query();
			
		}
		
		
		return 1;
	
	}





	function updateCategoryStats( $category_id) {

		$db	=& JFactory::getDBO();			


		// get latest posting date in this category
		$sql = "SELECT date FROM ".$db->nameQuote('#__discussions_messages')." WHERE cat_id='". $category_id . 
				"' AND published='1' ORDER BY DATE DESC LIMIT 1";
		
		$db->setQuery( $sql);
		$_last_entry_date = $db->loadResult();


		// get latest posting user_id in this category
		$sql = "SELECT user_id FROM ".$db->nameQuote('#__discussions_messages')." WHERE cat_id='". $category_id . 
				"' AND published='1' ORDER BY DATE DESC LIMIT 1";
		
		$db->setQuery( $sql);
		$_last_entry_user_id = $db->loadResult();


		// get # of threads in category
		$sql = "SELECT count(*) FROM ".$db->nameQuote('#__discussions_messages')." WHERE cat_id='". $category_id . 
				"' AND published='1' AND parent_id = '0'";
		
		$db->setQuery( $sql);
		$_counter_category_threads = $db->loadResult();


		// get # of posts in category
		$sql = "SELECT count(*) FROM ".$db->nameQuote('#__discussions_messages')." WHERE cat_id='". $category_id . 
				"' AND published='1'";
		
		$db->setQuery( $sql);
		$_counter_category_posts = $db->loadResult();



		$sql = "UPDATE ".$db->nameQuote( '#__discussions_categories') . 
				" SET last_entry_date ='".$_last_entry_date."', " .
				 "last_entry_user_id ='".$_last_entry_user_id."', " . 
				 "counter_threads ='".$_counter_category_threads."', " . 
				 "counter_posts ='".$_counter_category_posts."'" . 
				" WHERE id = '".$category_id."'";

		$db->setQuery( $sql);

		return $db->query();	
	
	}



	function updateAllCategoryStats() {

		$db	=& JFactory::getDBO();			


		// get all categories in db
        $sql = "SELECT id FROM ".$db->nameQuote( '#__discussions_categories');
        $db->setQuery( $sql);
        
        $_category_list = $db->loadAssocList();
        
		reset( $_category_list);
		while (list($key, $val) = each( $_category_list)) {
		
        	$category_id = $_category_list[$key]['id'];


			// get latest posting date in this category
			$sql = "SELECT date FROM ".$db->nameQuote('#__discussions_messages')." WHERE cat_id='". $category_id . 
					"' AND published='1' ORDER BY DATE DESC LIMIT 1";
			
			$db->setQuery( $sql);
			$_last_entry_date = $db->loadResult();
	
	
			// get latest posting user_id in this category
			$sql = "SELECT user_id FROM ".$db->nameQuote('#__discussions_messages')." WHERE cat_id='". $category_id . 
					"' AND published='1' ORDER BY DATE DESC LIMIT 1";
			
			$db->setQuery( $sql);
			$_last_entry_user_id = $db->loadResult();
	
	
			// get # of threads in category
			$sql = "SELECT count(*) FROM ".$db->nameQuote('#__discussions_messages')." WHERE cat_id='". $category_id . 
					"' AND published='1' AND parent_id = '0'";
			
			$db->setQuery( $sql);
			$_counter_category_threads = $db->loadResult();
	
	
			// get # of posts in category
			$sql = "SELECT count(*) FROM ".$db->nameQuote('#__discussions_messages')." WHERE cat_id='". $category_id . 
					"' AND published='1'";
			
			$db->setQuery( $sql);
			$_counter_category_posts = $db->loadResult();
	
	
	
			$sql = "UPDATE ".$db->nameQuote( '#__discussions_categories') . 
					" SET last_entry_date ='".$_last_entry_date."', " .
					 "last_entry_user_id ='".$_last_entry_user_id."', " . 
					 "counter_threads ='".$_counter_category_threads."', " . 
					 "counter_posts ='".$_counter_category_posts."'" . 
					" WHERE id = '".$category_id."'";
	
			$db->setQuery( $sql);
	
			$result = $db->query();
			
		}		
		
		return 1;
	
	}



	function updateUserStats( $user_id) {

		$db	=& JFactory::getDBO();			


		// get # posts of this user
		$sql = "SELECT count(*) FROM " . $db->nameQuote('#__discussions_messages') . 
					" WHERE user_id='". $user_id . "' AND published='1'";
		
		$db->setQuery( $sql);
		$_count = $db->loadResult();

					
		// update user stats
		$sql = "UPDATE " . $db->nameQuote( '#__discussions_users') . 
				" SET posts='" . $_count . "'" . 
				" WHERE id = '" . $user_id . "'";

		$db->setQuery( $sql);
		
		return $db->query();	
	
	}



	function acceptPost( $post_id) {

		$db	=& JFactory::getDBO();		



		// set WFM to 0 and published to 1
     	$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages') . 
				" SET wfm = '0'" . "," .
				" published = '1'" .
				" WHERE id = '".$post_id."'";
        
        $db->setQuery( $sql);
		$db->query();	



		// update the stats now

		// get user id of this post
		$sql = "SELECT user_id FROM ".$db->nameQuote('#__discussions_messages')." WHERE id='". $post_id . "'";
		
		$db->setQuery( $sql);
		$_user_id = $db->loadResult();


		// get thread id of this post
		$sql = "SELECT thread FROM ".$db->nameQuote('#__discussions_messages')." WHERE id='". $post_id . "'";
		
		$db->setQuery( $sql);
		$_thread_id = $db->loadResult();

		// get category id of this post
		$sql = "SELECT cat_id FROM ".$db->nameQuote('#__discussions_messages')." WHERE id='". $post_id . "'";
		
		$db->setQuery( $sql);
		$_cat_id = $db->loadResult();


		// set user post counter ++
		$result = $this->increaseUserPostCounter( $_user_id);
		
		
		// todo check if user is no rookie any more
		$postUser = new CofiUser( $_user_id);

		// get Rookie Mode setting from com_discussions parameters
		$params = JComponentHelper::getParams('com_discussions');
		$rookie = $params->get('rookie', '0');

		if ( $postUser->isRookie()) { // check if he is now no rookie any more
			if ( $postUser->getPosts() >= $rookie) {
				$postUser->setRookie( 0);
			}
		}

				
		// update thread stats
		$result = $this->updateThreadStats( $_thread_id);
									
		// update category stats
		$result = $this->updateCategoryStats( $_cat_id);									


		return 0;
	
	}



	function denyPost( $post_id) {

		$db	=& JFactory::getDBO();		


		// delete denied post from db
     	$sql = "DELETE FROM ".$db->nameQuote( '#__discussions_messages') . " WHERE id = '".$post_id."'";
        
        $db->setQuery( $sql);
		$db->query();	

		return 0;
	
	}



	function sendEmailToModeratorsPostWFM() {

		// get settings from com_discussions parameters
		$params = JComponentHelper::getParams('com_discussions');
		
		$SiteName 	= $params->get('emailSiteName', '');
		$from 		= $params->get('emailFrom', '');
		$sender 	= $params->get('emailSender', '');
		$link 		= $params->get('emailLink', '');
		$subject 	= $params->get('emailWFMSubject', '');
		$msgparam 	= $params->get('emailWFMMessage', '');


		jimport( 'joomla.mail.helper' );

		$db	=& JFactory::getDBO();		

		// get all moderators with email notifications set     
        $sql = "SELECT u.username, u.email FROM ".$db->nameQuote( '#__users') . " u, " .$db->nameQuote( '#__discussions_users') . " d" .
        		" WHERE u.id = d.id AND d.moderator = 1 AND d.email_notification = 1";
                
        $db->setQuery( $sql);
        
        $_moderator_list = $db->loadAssocList();
        
		reset( $_moderator_list);
		while (list($key, $val) = each( $_moderator_list)) {
		
        	$username = $_moderator_list[$key]['username'];
        	
        	$email    = $_moderator_list[$key]['email'];

    		if ( JMailHelper::isEmailAddress( $email)) {

	    		// construct email
	    						
	    		$msg     	= $username. ", \n\n" . $msgparam;

				$body	 = sprintf( $msg, $SiteName, $sender, $from, $link);

				// Clean the email data
				$subject = JMailHelper::cleanSubject( $subject);
				$body	 = JMailHelper::cleanBody( $body);
				$sender	 = JMailHelper::cleanAddress( $sender);


				JUtility::sendMail( $from, $sender, $email, $subject, $body);
	    		    		
    		}
    		
    		
		}        
               
		return 0;
	
	}



	function isCategoryModerated( $cat_id) {

		$db	=& JFactory::getDBO();		

		// get user id of this post
		$sql = "SELECT moderated FROM ".$db->nameQuote('#__discussions_categories')." WHERE id='". $cat_id . "'";
				
		$db->setQuery( $sql);
		$_moderated = $db->loadResult();

		if ( $_moderated == 0) {
			return false;
		}
		else {
			return true;
		}

	}

	function getCategoryNameById( $id) {

		$db	=& JFactory::getDBO();		

		$sql = "SELECT name FROM ".$db->nameQuote('#__discussions_categories')." WHERE id='". $id . "'";

		$db->setQuery( $sql);
		$categoryname = $db->loadResult();

		if ( !$categoryname) {
			return "-";
		}
		else {
			return $categoryname;
		}

	}


    function getCategorySlugById( $id) {

        $db	=& JFactory::getDBO();

        $sql = "SELECT CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(':', id, alias) ELSE id END as cslug" .
                " FROM " . $db->nameQuote('#__discussions_categories') .
                " WHERE id='" . $id . "' AND published='1' AND private='0'";


        $db->setQuery( $sql);
        $categoryslug = $db->loadResult();

        if ( !$categoryslug) {
            return "-";
        }
        else {
            return $categoryslug;
        }

    }


    function getThreadSlugById( $id) {

        $db	=& JFactory::getDBO();

        $sql = "SELECT CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(':', id, alias) ELSE id END as tslug" .
                " FROM " . $db->nameQuote('#__discussions_messages') .
                " WHERE id='" . $id . "' AND parent_id='0' AND published='1'";


        $db->setQuery( $sql);
        $threadslug = $db->loadResult();

        if ( !$threadslug) {
            return "-";
        }
        else {
            return $threadslug;
        }

    }


	function getQuickJumpSelectBox( $cat_id) {

		$user =& JFactory::getUser();
		$logUser = new CofiUser( $user->id);

		$db	=& JFactory::getDBO();		

        
        // create array for slugs
        $slugs = array();

		// set slug counter to 0
        $iSlug = 0;
        
	
		$html = "<select class='quickselectbox' name='quickselectbox' onchange='callURL( this)'>";

		if ( $logUser->isModerator() == 1) { 
			// get all published category groups
		    $sql_groups = "SELECT id, name FROM ".$db->nameQuote( '#__discussions_categories') . 
		    		" WHERE parent_id='0' AND published='1'" .
		    		" ORDER BY ordering ASC";
        }
        else {  // get only public published category groups
		    $sql_groups = "SELECT id, name FROM ".$db->nameQuote( '#__discussions_categories') . 
		    		" WHERE parent_id='0' AND private='0' AND published='1'" .
		    		" ORDER BY ordering ASC";        
        }        
                                
        $db->setQuery( $sql_groups);
        
        $_group_list = $db->loadAssocList();

		reset( $_group_list);
		while (list($key, $val) = each( $_group_list)) {
		
        	$group_id 	= $_group_list[$key]['id'];        	
        	$group_name = $_group_list[$key]['name'];

			$html .= "<optgroup label='".$group_name."'>";


				/* get categories from this group */
				if ( $logUser->isModerator() == 1) { 
					// get all published categories in this group
				    $sql_categories = "SELECT id, name, " . 
				    		" CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(':', id, alias) ELSE id END as slug" .
				    		" FROM ".$db->nameQuote( '#__discussions_categories') . 
				    		" WHERE parent_id='".$group_id."' AND published='1'" .
				    		" ORDER BY ordering ASC";
		        }
		        else {  // get only public published categories in this group
				    $sql_categories = "SELECT id, name, " .
				    		" CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(':', id, alias) ELSE id END as slug" .
				    		" FROM ".$db->nameQuote( '#__discussions_categories') . 
				    		" WHERE parent_id='".$group_id."' AND private='0' AND published='1'" .
				    		" ORDER BY ordering ASC";        
		        }        

		        $db->setQuery( $sql_categories);
		        
		        $_category_list = $db->loadAssocList();
		
				reset( $_category_list);
				while (list($key, $val) = each( $_category_list)) {
				
		        	$category_id 	 = $_category_list[$key]['id'];        	
		        	$category_name   = $_category_list[$key]['name'];
		        	$category_slug   = $_category_list[$key]['slug'];
		
					if ( $category_id == $cat_id) { // this category is the current active
						$html .= "<option value='".$category_id."' selected='selected'>" . $category_name;
					}
					else {
						$html .= "<option value='".$category_id."'>" . $category_name;
					}
					
					$category_urlTMP = "index.php?option=com_discussions&view=category&catid=".$category_slug;
        			$category_url = JRoute::_( $category_urlTMP);

					
					$slugs[$iSlug][0] = utf8_encode( $category_id); 		
					$slugs[$iSlug][1] = utf8_encode( $category_url);
					$iSlug++;
					
				}
				/* get categories from this group */

			$html .= "</optgroup>";

		}

		$html .=	"</select>";
		
		print "<script type='text/javascript'> var slugsarray = ".json_encode($slugs)."; </script>";        

		
		return $html;

	}



	function getMoveToSelectBox() {

		$user =& JFactory::getUser();
		$logUser = new CofiUser( $user->id);

		if ( $logUser->isModerator() == 1) { 

			$db	=& JFactory::getDBO();		

			$html = "<select class='quickselectbox' name='catidto'>";

			// get all published category groups
		    $sql_groups = "SELECT id, name FROM ".$db->nameQuote( '#__discussions_categories') . 
		    		" WHERE parent_id='0' AND published='1'" .
		    		" ORDER BY ordering ASC";
                                
        	$db->setQuery( $sql_groups);
        
        	$_group_list = $db->loadAssocList();

			reset( $_group_list);
			while (list($key, $val) = each( $_group_list)) {
		
        		$group_id 	= $_group_list[$key]['id'];        	
        		$group_name = $_group_list[$key]['name'];

				$html .= "<optgroup label='".$group_name."'>";

				/* get categories from this group */
				$sql_categories = "SELECT id, name FROM ".$db->nameQuote( '#__discussions_categories') . 
				    		" WHERE parent_id='".$group_id."' AND published='1'" .
				    		" ORDER BY ordering ASC";

		        $db->setQuery( $sql_categories);
		        
		        $_category_list = $db->loadAssocList();
		
				reset( $_category_list);
				while (list($key, $val) = each( $_category_list)) {
				
		        	$category_id 	= $_category_list[$key]['id'];        	
		        	$category_name  = $_category_list[$key]['name'];
		
					$html .= "<option value='".$category_id."'>" . $category_name;
				
				}
				/* get categories from this group */

				$html .= "</optgroup>";

			}

			$html .= "</select>";
		
			return $html;
		
		} // end if moderator
		else {  // return an empty string when not moderator
		
			return "";
			
		}
	}



	function getUsernameById( $id) {

		$db	=& JFactory::getDBO();		

		$sql = "SELECT username FROM ".$db->nameQuote('#__users')." WHERE id='". $id . "'";
				
		$db->setQuery( $sql);
		$username = $db->loadResult();

		if ( !$username) {
			return "-";
		}
		else {
			return $username;
		}

	}


	function getZipcodeById( $id) {

		$db	=& JFactory::getDBO();		

		$sql = "SELECT zipcode FROM ".$db->nameQuote('#__users')." WHERE id='". $id . "'";
				
		$db->setQuery( $sql);
		$zipcode = $db->loadResult();

		if ( !$zipcode) {
			return "";
		}
		else {
			return $zipcode;
		}

	}


	function getCityById( $id) {

		$db	=& JFactory::getDBO();		

		$sql = "SELECT city FROM ".$db->nameQuote('#__users')." WHERE id='". $id . "'";
				
		$db->setQuery( $sql);
		$city = $db->loadResult();

		if ( !$city) {
			return "";
		}
		else {
			return $city;
		}

	}


	function getCountryById( $id) {

		$db	=& JFactory::getDBO();		

		$sql = "SELECT country FROM ".$db->nameQuote('#__users')." WHERE id='". $id . "'";
				
		$db->setQuery( $sql);
		$country = $db->loadResult();

		if ( !$country) {
			return "";
		}
		else {
			return $country;
		}

	}


	function isPermittedForImageAttachmentsById( $id) {

		$_permission = 0;

		$db	=& JFactory::getDBO();		


		// get parameters
		$params = JComponentHelper::getParams('com_discussions');

		// 1. check if image attachments are allowed
		$_imagesAllowed = $params->get('imagesAllowed', '0');
		
		if ( $_imagesAllowed != 0) { // image attachments are allowed

			switch ( $_imagesAllowed) {

				case 1: { // Moderators

					// check if user is moderator
					$sql = "SELECT moderator FROM ".$db->nameQuote('#__discussions_users')." WHERE id='". $id . "'";				
							
					$db->setQuery( $sql);
					$_isModerator = $db->loadResult();
					
					if ( $_isModerator == 1) { // user is moderator
						$_permission = 1;
					}
					else {
						$_permission = 0;
					}	
					break;
					
				}
				
				case 2: { // Selected users
								
					// check if user is permitted
					$sql = "SELECT images FROM ".$db->nameQuote('#__discussions_users')." WHERE id='". $id . "'";				
							
					$db->setQuery( $sql);
					$_isImages = $db->loadResult();
					
					if ( $_isImages == 1) { // user is permitted
						$_permission = 1;
					}
					else {
						$_permission = 0;
					}	
					break;
					
				}
				
				case 3: { // Non rookies

					// check if user is not a rookie anymore
					$sql = "SELECT rookie FROM ".$db->nameQuote('#__discussions_users')." WHERE id='". $id . "'";				
							
					$db->setQuery( $sql);
					$_isRookie = $db->loadResult();
					
					if ( $_isRookie == 0) { // user is no rookie
						$_permission = 1;
					}
					else {
						$_permission = 0;
					}	
				
					break;
				}

				case 10: { // All users
					$_permission = 1;
					break;
				}

				default: {
					$_permission = 0; // default = don't allow image attachments
					break;
				}

			}
				
		}
			
			
		if ( $_permission == 0) { // additionally check if we give can give permission to users in AEC plans
			
			$_imagesAllowedAEC = $params->get('imagesAllowedAEC', '0');
			
			if  ( $_imagesAllowedAEC == 1) { // check if user is in an AEC plan which has permission
			
				$_imagesAllowedAECPlans = $params->get('imagesAllowedAECPlans', '');
			
				$plan = strtok( $_imagesAllowedAECPlans, ",");
				
				while ( $plan !== false) {
				
					$sql = "SELECT userid FROM ".$db->nameQuote('#__acctexp_subscr') . 
							" WHERE plan='". $plan . "' AND userid='" . $id . "' AND status='Active' ";
														
					$db->setQuery( $sql);
					$result = $db->loadResult();

					if ( $result) { // got a result
						$_permission = 1;
					}
	    			
	    			$plan = strtok(",");
	    			
				}
			
			}
			
		}	
			
						
		return $_permission;

	}


	function getVersion() {

		$db	=& JFactory::getDBO();		

		$sql = "SELECT version FROM ".$db->nameQuote('#__discussions_meta')." WHERE id='1'";
				
		$db->setQuery( $sql);
		$version = $db->loadResult();

		if ( !$version) {
			return "0";
		}
		else {
			return $version;
		}

	}


	function getItemidByComponentName( $component) {

		$db	=& JFactory::getDBO();		

		$sql = "SELECT id FROM " . $db->nameQuote('#__components') . " WHERE " . $db->nameQuote('option') . "='" . $component . "'";
					
		$db->setQuery( $sql);
		$componentid = $db->loadResult();

		if ( !$componentid) {
		
			return 0;
			
		}
		else {

			$sql = "SELECT id FROM " . $db->nameQuote('#__menu') . 
					" WHERE " . $db->nameQuote('componentid') . "='" . $componentid . "' AND parent='0' AND published='1' ";
					
			$db->setQuery( $sql);
			$itemid = $db->loadResult();
	
			if ( !$itemid) {
				return 0;
			}
			else {
				return $itemid;
			}
		
		}

	}


	function getNumberOfPostsByThreadId( $id) {

		$db	=& JFactory::getDBO();		


		$sql = "SELECT counter_replies + 1 FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $id . "' AND parent_id='0' AND published='1'";
				
		$db->setQuery( $sql);
		$result = $db->loadResult();

		return $result;

/*
		$sql = "SELECT count(*) FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $id . "' AND published='1'";
				
		$db->setQuery( $sql);
		$result = $db->loadResult();

		if ( !$result) {
			return 0;
		}
		else {
			return $result;
		}

*/

	}


	function getLastPostIdByThreadId( $id) {

		$db	=& JFactory::getDBO();		

		$sql = "SELECT id FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $id . 
			"' AND published='1' ORDER BY id DESC LIMIT 1";
				
		$db->setQuery( $sql);
		$result = $db->loadResult();

		if ( !$result) {
			return 0;
		}
		else {
			return $result;
		}

	}



    function getPostingJumpPointByThreadIdAndPostingId( $thread_id, $posting_id) {

        $db	=& JFactory::getDBO();

        // get parameters
        $params = JComponentHelper::getParams('com_discussions');        

        $_threadListLength 	= $params->get('threadListLength', '20'); // get configured list length for threads

        $jumpPoint = ""; // default no jump point

        $sql = "SELECT id FROM ".$db->nameQuote('#__discussions_messages')." WHERE thread='". $thread_id .
            "' AND published='1' ORDER BY date ASC";

        $db->setQuery( $sql);

        $_postingList = $db->loadAssocList();

        $i = 1;
		reset( $_postingList);
		while (list($key, $val) = each( $_postingList)) {

        	$_id = $_postingList[$key]['id'];

    		if ( $_id == $posting_id) { // found posting in result set


                if ( ( $i % $_threadListLength) == 0) {
                    $_start = ( $i / $_threadListLength) - 1;
                }
                else {
                    $_start = floor( $i / $_threadListLength);
                }

                $_start = $_start * $_threadListLength;


                if ( $_start == 0) {  // first page = no limitstart
                    $jumpPoint = "#p" . $posting_id;
                }
                else {
                    $jumpPoint = "&limitstart=" . $_start ."#p" . $posting_id;
                }

    		}

            $i++;
		}

        return $jumpPoint;

    }



	function del_image( $thread, $id, $image) {
		
		// get folder name		
		$rootDir = JPATH_ROOT;		

		$db	=& JFactory::getDBO();		
		
	    $image_folder = $rootDir . "/images/discussions/posts/" . $thread . "/";    
		
        // get image name
        $sql = "SELECT " . $image . " FROM #__discussions_messages WHERE id=".$id;	
		$db->setQuery( $sql);
		$imagename = $db->loadResult();

		if ( $imagename != "") {

			$original_image = $image_folder . $id . "/original/" . $imagename;
			$large_image 	= $image_folder . $id . "/large/" . $imagename;
			$small_image 	= $image_folder . $id . "/small/" . $imagename;

            if ( file_exists( $original_image)) {
                unlink( $original_image);
            }
            if ( file_exists( $large_image)) {
                unlink( $large_image);
            }
            if ( file_exists( $small_image)) {
                unlink( $small_image);
            }
            
		}	
	        
	}


	function deleteImagesByPostId( $id) {
		
		// get folder name		
		$rootDir = JPATH_ROOT;		

		$db	=& JFactory::getDBO();		

        $sql = "SELECT thread FROM #__discussions_messages WHERE id=".$id;	
		$db->setQuery( $sql);
		$thread = $db->loadResult();
				
		$folder = $rootDir . "/images/discussions/posts/" . $thread . "/" . $id;

		// 1. delete all existing images for this post
        // get image names
        $sql = "SELECT image1, image2, image3, image4, image5 FROM #__discussions_messages WHERE id=".$id;
		$db->setQuery( $sql);
		$rows = $db->loadObjectList();

		foreach ( $rows as $row ) {

			if ( $row->image1 != "") {
				$this->del_image( $thread, $id, "image1");	
			}
			if ( $row->image2 != "") {
				$this->del_image( $thread, $id, "image2");
			}
			if ( $row->image3 != "") {
				$this->del_image( $thread, $id, "image3");
			}
			if ( $row->image4 != "") {
				$this->del_image( $thread, $id, "image4");
			}
			if ( $row->image5 != "") {
				$this->del_image( $thread, $id, "image5");
			}				
		}

    
    	// 2. remove all existing folders for this post
    	
    	if (is_dir( $folder. "/original/")) {
    		rmdir( $folder. "/original/");
    	}
    	if (is_dir( $folder. "/large/")) {
    		rmdir( $folder. "/large/");
    	}
    	if (is_dir( $folder. "/small/")) {
    		rmdir( $folder. "/small/");
    	}    	
    	if (is_dir( $folder)) {
    		rmdir( $folder );
    	}

	        
	}



    function getReplyRecentListByThreadId( $id, $number) {

        if ( $id == '0' || $id == '') {
            return "";
        }
        else {

            $_html = "";

            // website root directory
            $_root = JURI::root();

	        $params = JComponentHelper::getParams('com_discussions');        
			$_dateformat	= $params->get( 'dateformat', '%d.%m.%Y');
			$_timeformat	= $params->get( 'timeformat', '%H:%i');        		        	        		        		


            $db	=& JFactory::getDBO();
            

            $sql = "SELECT user_id, DATE_FORMAT( date, '" . $_dateformat . " " . $_timeformat . "') AS rdate, message FROM ".$db->nameQuote('#__discussions_messages') .
                    " WHERE thread='" . $id . "' AND published='1' ORDER BY date DESC LIMIT " . $number;

            $db->setQuery( $sql);
            $rows = $db->loadObjectList();


            $_html = "<table cellspacing='0' cellpadding='0' >";

            foreach ( $rows as $row ) {

                $rUsername = $this->getUsernameById($row->user_id);
                $rUser = new CofiUser( $row->user_id);

                $_html .= "<tr>";

                    $_html .= "<td width='50px' style='border-top: 1px dotted #CCC;'>";

                        $_html .= "<div class='cofiCategoryAvatarBox'>";

                            if ( $rUser->getAvatar() == "") { // display default avatar
                                $_html .= "<img src='" . $_root . "components/com_discussions/assets/users/user.png' class='cofiCategoryDefaultAvatar' alt='$rUsername' title='$rUsername' />";
                            }
                            else { // display uploaded avatar
                                $_html .= "<img src='" . $_root . "images/discussions/users/".$row->user_id."/small/".$rUser->getAvatar()."' class='cofiCategoryAvatar' alt='$rUsername' title='$rUsername' />";
                            }

                        $_html .= "</div>";

                    $_html .= "</td>";

                    $_html .= "<td style='border-top: 1px dotted #CCC;'>";
                        $_html .= "<b>" . $rUsername . "</b>";
                        $_html .= "<br />";
                        $_html .= $row->rdate;
                        $_html .= "<br />";
                    $_html .= "</td>";

                $_html .= "</tr>";


                $_html .= "<tr>";

                    $_html .= "<td colspan='2' style='padding: 0px 10px 10px 10px;'>";
                    $_html .= $row->message;
                    $_html .= "</td>";

                $_html .= "</tr>";

            }

            $_html .= "</table>";



            if ( !$rows) {
                return "";
            }
            else {
                return $_html;
            }
            
        }
    }



	function isUserOnlineById( $id) {

		$db	=& JFactory::getDBO();		

		$sql = 'SELECT count(*)' .
				' FROM #__session' .
				' WHERE userid=' . $id ;			
							
		$db->setQuery( $sql);
		$_count = $db->loadResult();

		if ( $_count == 0) {
			return false;
		}
		else {
			return true;
		}

	}





}







