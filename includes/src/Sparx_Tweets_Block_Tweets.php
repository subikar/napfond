<?php   
class Sparx_Tweets_Block_Tweets extends Mage_Core_Block_Template{   

	/*
	 * Get Tweets By Username
	 * return array
	*/ 
	public function getTweetsByUsername ($username)
	{	
		$feed = Mage::getModel('tweets/feed')->getFeed($username);	
		return($this->getTweets($feed));
	}
	function getTime($time){
		 $tweetdate = $time;
		 $tweet = $tweettag["content"];
		 $timedate = explode(" ",$tweetdate);
		 $date1 = $timedate[2];
		 $date2 = $timedate[1];
		 $date3 = $timedate[5];
		 $time = substr($timedate[3],0, -1);
		 $tweettime = (strtotime($date1." ".$date2." ".$date3." ".$time))+3600; // This is the value of the time difference - UK + 1 hours (3600 seconds)
		 $nowtime = time();
		 $timeago = ($nowtime-$tweettime);
		 $thehours = floor($timeago/3600);
		 $theminutes = floor($timeago/60);
		 $thedays = floor($timeago/86400);
		 /********************* Checking the times and returning correct value */
		 if($theminutes < 60){
		 if($theminutes < 1){
		 $timemessage =  "Less than 1 minute ago";
		 } else if($theminutes == 1) {
		 $timemessage = $theminutes." minute ago.";
		 } else {
		 $timemessage = $theminutes." minutes ago.";
		 }
		 } else if($theminutes > 60 && $thedays < 1){
		 if($thehours == 1){
		 $timemessage = $thehours." hour ago.";
		 } else {
		 $timemessage = $thehours." hours ago.";
		 }
		 } else {
		 if($thedays == 1){
		 $timemessage = $thedays." day ago.";
		 } else {
		 $timemessage = $thedays." days ago.";
		 }
		 }
		 return $timemessage;
	}
	public function getTweets($tweets)
	{
		$t = array();
		$i = 0;
		foreach($tweets as $tweet)
		{	
			$text = $tweet['text'];
			$urls = $tweet['entities']['urls'];
			$mentions = $tweet['entities']['user_mentions'];
			$hashtags = $tweet['entities']['hashtags'];
			if($urls){
				foreach($urls as $url){
					if(strpos($text,$url['url']) !== false){
						$text = str_replace($url['url'],'<a href="'.$url['url'].'">'.$url['url'].'</a>',$text);	
					}
				}
			}
			/*
			if($mentions && $this->getAttags()){
				foreach($mentions as $mention){
					if(strpos($text,$mention->screen_name) !== false){
						$text = str_replace("@".$mention->screen_name." ",'<a href="http://twitter.com/'.$mention->screen_name.'">@'.$mention->screen_name.'</a> ',$text);	
					}
				}
			}
			if($hashtags && $this->getHashtags()){
				foreach($hashtags as $hashtag){
					if(strpos($text,$hashtag->text) !== false){
						$text = str_replace('#'.$hashtag->text." ",'<a href="http://twitter.com/search?q=%23'.$hashtag->text.'">#'.$hashtag->text.'</a> ',$text);	
					}
				}
			}
			*/
			//$t[$i]["tweet"] = trim($this->changeLink($text, $this->getTagPref(), $this->getNoFollow(), $this->getNewWindow()));	
			$t[$i]["text"] = $text;
			$t[$i]["created_at"] = trim($this->getTime($tweet['created_at']));
			$i++;
		}		
		return $t;
	}
}
