// JavaScript Document
function check_validation()
{
	var count = counter;
	for(i=1;i<=count;i++)
	{
		var myRegExp =/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
		
		var link_val = document.getElementById("link_"+i).value;
		if(link_val != '')
		{
			if (!myRegExp.test(link_val))
			{
				document.getElementById("error_"+i).innerHTML = 'Invalid Link';
				return false;
			}
			else
			{
				document.getElementById("error_"+i).innerHTML = '';
			}
		}
	}	
}

function div_echeck(str)
{var at="@"
var dot="."
var lat=str.indexOf(at)
var lstr=str.length
var ldot=str.indexOf(dot)
if(str.indexOf(at)==-1){return false}
if(str.indexOf(at)==-1||str.indexOf(at)==0||str.indexOf(at)==lstr){return false}
if(str.indexOf(dot)==-1||str.indexOf(dot)==0||str.indexOf(dot)==lstr){return false}
if(str.indexOf(at,(lat+1))!=-1){return false}
if(str.substring(lat-1,lat)==dot||str.substring(lat+1,lat+2)==dot){return false}
if(str.indexOf(dot,(lat+2))==-1){return false}
if(str.indexOf(" ")!=-1){return false}
return true}
function isNumber(evt)
{var keynum;if(window.event)
{keynum=evt.keyCode;}
else if(evt.which)
{keynum=evt.which;}
var charCode=(evt.which)?evt.which:keynum
if(charCode>31&&((charCode<48||charCode>57)&&charCode!=32))
return false;return true;}