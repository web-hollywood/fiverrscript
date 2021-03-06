<?php
function filter_scriptolution_messages($var)
{
	$text = $var;
	$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $text);
	$ret = ' ' . $text;
	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1scriptolutionreplacement", $ret);
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1scriptolutionreplacement", $ret);
	$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1scriptolutionreplacement", $ret);
	$ret = substr($ret, 1);
	
	if (preg_match("/scriptolutionreplacement/i", $ret)) 
	{
		return "1";
	} 
	else 
	{
		return "0";
	}
}

function insert_get_packs($a)
{
    global $config,$conn;
	if($config['enable_levels'] == "1" && $config['price_mode'] == "3")
	{
		$me = intval($_SESSION['USERID']);
		if($me > "0")
		{
			$query = "select level from members where USERID='".mysqli_real_escape_string($conn->_connectionID, $me)."'"; 
			$executequery=$conn->execute($query);
			$mlevel = intval($executequery->fields['level']);
			if($mlevel == "3")
			{
				$addl = "WHERE l3='1'";
			}
			elseif($mlevel == "2")
			{
				$addl = "WHERE l2='1'";
			}
			elseif($mlevel == "1")
			{
				$addl = "WHERE l1='1'";
			}
		}
	}
	$query = "select ID,pprice from packs $addl order by pprice asc"; 
	$results = $conn->execute($query);
	$returnthis = $results->getrows();
	return $returnthis;
}

function insert_get_seo_profile($a)
{
        $uname = $a['username'];
		echo "user/".$uname;
}

function get_seo_profile($uname)
{
		return "user/".$uname;
}

function insert_get_seo_convo($a)
{
        $uname = $a['username'];
		echo "conversations/".$uname;
}

function insert_add_plus($a)
{
	global $lang, $config;
	$p = $a['price'];
    $tt = $lang['62']."+".$a['topic']."+".$lang['63'].$p;
	$tt = str_replace(' ', '+', $tt);
	echo $tt;
}

function insert_get_percent($a)
{
	global $conn;
	$query = "select good, bad from ratings where USERID='".mysqli_real_escape_string($conn->_connectionID, $a[userid])."'"; 
	$results=$conn->execute($query);
	$f = $results->getrows();
	$grat = 0;
	$brat = 0;
	for($i=0;$i<count($f);$i++)
	{
		$tgood = $f[$i]['good'];
		$tbad = $f[$i]['bad'];
		if($tgood == "1")
		{
			$grat++;	
		}
		elseif($tbad == "1")
		{
			$brat++;	
		}
	}
	$g = $grat;
	$b = $brat;
	$t = $g + $b;
	if($t > 0)
	{
		$r = (($g / $t) * 100);
		return round($r, 1);
	}
	else
	{
		return 0;
	}
}

function insert_get_percent2($a)
{
    global $conn;
	$query = "select good, bad from ratings where USERID='".mysqli_real_escape_string($conn->_connectionID, $_SESSION[USERID])."'"; 
	$results=$conn->execute($query);
	$f = $results->getrows();
	$grat = 0;
	$brat = 0;
	for($i=0;$i<count($f);$i++)
	{
		$tgood = $f[$i]['good'];
		$tbad = $f[$i]['bad'];
		if($tgood == "1")
		{
			$grat++;	
		}
		elseif($tbad == "1")
		{
			$brat++;	
		}
	}
	$g = $grat;
	$b = $brat;
	$t = $g + $b;
	if($t > 0)
	{
		$r = (($g / $t) * 100);
		return round($r, 1);
	}
	else
	{
		return 0;
	}
}

function insert_get_rating($a)
{
	$g = $a['g'];
	$b = $a['b'];
	$t = $g + $b;
	if($t > 0)
	{
		$r = (($g / $t) * 100);
		return round($r, 1);
	}
	else
	{
		return 0;
	}
}

function insert_get_rating2($a)
{
    global $conn;
	$query = "select A.good, A.bad from ratings A, members B where A.PID='".mysqli_real_escape_string($conn->_connectionID, $a[pid])."' AND A.RATER=B.USERID and B.status='1'"; 
	$results=$conn->execute($query);
	$f = $results->getrows();
	$grat = 0;
	$brat = 0;
	for($i=0;$i<count($f);$i++)
	{
		$tgood = $f[$i]['good'];
		$tbad = $f[$i]['bad'];
		if($tgood == "1")
		{
			$grat++;	
		}
		elseif($tbad == "1")
		{
			$brat++;	
		}
	}
	$g = $grat;
	$b = $brat;
	$t = $g + $b;
	if($t > 0)
	{
		$r = (($g / $t) * 100);
		return round($r, 1);
	}
	else
	{
		return 0;
	}
}

function insert_get_gtitle($a)
{
    global $conn;
	$query = "select A.gtitle from posts A, orders B where B.OID='".mysqli_real_escape_string($conn->_connectionID, $a[oid])."' AND B.PID=A.PID"; 
	$executequery=$conn->execute($query);
	$gtitle = $executequery->fields['gtitle'];
	return $gtitle;
}

function insert_last_unread($a)
{
    global $conn;
	$mto = intval($a['uid']);
	$mfr = intval($_SESSION['USERID']);
	$query = "select MSGTO, unread from inbox where ((MSGTO='".mysqli_real_escape_string($conn->_connectionID, $mto)."' AND MSGFROM='".mysqli_real_escape_string($conn->_connectionID, $mfr)."') OR (MSGTO='".mysqli_real_escape_string($conn->_connectionID, $mfr)."' AND MSGFROM='".mysqli_real_escape_string($conn->_connectionID, $mto)."')) order by MID desc limit 1"; 
	$executequery=$conn->execute($query);
	$unread = $executequery->fields['unread'];
	$MSGTO = $executequery->fields['MSGTO'];
	if($MSGTO == $mfr)
	{
		return $unread;
	}
	else
	{
		return 0;	
	}
}

function insert_last_email($a)
{
    global $conn;
	$mto = intval($a['uid']);
	$mfr = intval($_SESSION['USERID']);
	$query = "select message from inbox where ((MSGTO='".mysqli_real_escape_string($conn->_connectionID, $mto)."' AND MSGFROM='".mysqli_real_escape_string($conn->_connectionID, $mfr)."') OR (MSGTO='".mysqli_real_escape_string($conn->_connectionID, $mfr)."' AND MSGFROM='".mysqli_real_escape_string($conn->_connectionID, $mto)."')) order by MID desc limit 1"; 
	$executequery=$conn->execute($query);
	$message = $executequery->fields['message'];
	return $message;

}

function insert_last_delivery($a)
{
    global $conn;
	$oid = intval($a['oid']);
	$query = "select MID from inbox2 where OID='".mysqli_real_escape_string($conn->_connectionID, $oid)."' AND action='delivery' order by MID desc limit 1"; 
	$executequery=$conn->execute($query);
	$MID = $executequery->fields['MID'];
	return $MID;

}

function insert_get_status($a)
{
    global $conn;
	$oid = intval($a['oid']);
	$query = "select status from orders where OID='".mysqli_real_escape_string($conn->_connectionID, $oid)."'"; 
	$executequery=$conn->execute($query);
	$status = $executequery->fields['status'];
	return $status;

}

function insert_fback($a)
{
    global $conn;
	$oid = intval($a['oid']);
	$query = "select count(*) as total from ratings where OID='".mysqli_real_escape_string($conn->_connectionID, $oid)."' AND RATER='".mysqli_real_escape_string($conn->_connectionID, $_SESSION[USERID])."'"; 
	$executequery=$conn->execute($query);
	$total = $executequery->fields['total']+0;
	return $total;

}

function insert_wdreq($a)
{
    global $conn;
	$oid = intval($a['oid']);
	$query = "select count(*) as total from withdraw_requests where USERID='".mysqli_real_escape_string($conn->_connectionID, $_SESSION[USERID])."'"; 
	$executequery=$conn->execute($query);
	$total = $executequery->fields['total']+0;
	return $total;

}

function insert_fback2($a)
{
    global $conn;
	$oid = intval($a['oid']);
	$sid = intval($a['sid']);
	$query = "select count(*) as total from ratings where OID='".mysqli_real_escape_string($conn->_connectionID, $oid)."' AND RATER='".mysqli_real_escape_string($conn->_connectionID, $sid)."'"; 
	$executequery=$conn->execute($query);
	$total = $executequery->fields['total']+0;
	return $total;

}

function insert_gig_details($a)
{
    global $conn;
	$query = "SELECT A.*, B.seo from posts A, categories B where A.active='1' AND A.category=B.CATID AND A.PID='".mysqli_real_escape_string($conn->_connectionID, $a[pid])."' limit 1";
	$results = $conn->execute($query);
	$w = $results->getrows();
	return $w;
}

function insert_file_details($a)
{
    global $conn;
	$query = "SELECT FID, fname, s from files where FID='".mysqli_real_escape_string($conn->_connectionID, $a[fid])."' limit 1";
	$results = $conn->execute($query);
	$w = $results->getrows();
	return $w;
}

function insert_gfs($a)
{
	global $conn, $config;
	$query = "select fname,s from files where FID='".mysqli_real_escape_string($conn->_connectionID, $a[fid])."' limit 1"; 
	$executequery=$conn->execute($query);
	$s = $executequery->fields['s'];
	$fname = $executequery->fields['fname'];
    $path = $config['basedir'].'/files/';
	$cf = md5($a['fid']).$s;
	$saveme = $path.$cf;
	$file_loc = $saveme."/".$fname; 
	return formatBytes(filesize($file_loc), 1);
}

function formatBytes($bytes, $precision = 2) 
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
} 

function insert_mark_read($a)
{
    global $conn;
	$query = "UPDATE inbox SET unread='0' WHERE MID='".mysqli_real_escape_string($conn->_connectionID, $a[mid])."'";
	$conn->execute($query);
}

function escape($data)
{
    if (ini_get('magic_quotes_gpc'))
	{
    	$data = stripslashes($data);
    }
    return mysqli_real_escape_string($conn->_connectionID, $data);
}

function insert_get_wants($var)
{
	global $conn, $config;
	$query="SELECT B.username, A.want FROM wants A, members B WHERE A.active='1' AND A.USERID=B.USERID order by rand() limit $config[max_suggest]";
	$results = $conn->execute($query);
	$w = $results->getrows();
	return $w;
}

function insert_get_categories($a)
{
    global $config,$conn;
	$query = "select * from categories where parent='0' order by name asc"; 
	$results = $conn->execute($query);
	$returnthis = $results->getrows();
	return $returnthis;
}

function insert_get_subcategories($a)
{
    global $config,$conn;
	$query = "select * from categories where parent='".mysqli_real_escape_string($conn->_connectionID, $a['parent'])."' order by name asc"; 
	$results = $conn->execute($query);
	$returnthis = $results->getrows();
	return $returnthis;
}

function insert_like_cnt($var)
{
    global $conn;
	$query = "select count(*) as total from bookmarks where USERID='".mysqli_real_escape_string($conn->_connectionID, $_SESSION[USERID])."' AND PID='".mysqli_real_escape_string($conn->_connectionID, $var[pid])."'"; 
	$executequery=$conn->execute($query);
	$cnt = $executequery->fields['total'];
	if($cnt > 0)
	{
		return "1";
	}
	else
	{
		return "0";
	}
}

function insert_active_orders($a)
{
    global $conn;
	$query = "select count(*) as total from orders where PID='".mysqli_real_escape_string($conn->_connectionID, $a[PID])."' AND (status='0' OR status='1' OR status='6')"; 
	$executequery=$conn->execute($query);
	$cnt = $executequery->fields['total'];
	return $cnt;

}

function insert_done_orders($a)
{
    global $conn;
	$query = "select count(*) as total from orders where PID='".mysqli_real_escape_string($conn->_connectionID, $a[PID])."' AND status='5'"; 
	$executequery=$conn->execute($query);
	$cnt = $executequery->fields['total'];
	return $cnt;

}

function insert_gig_cnt($var)
{
    global $conn;
	$query = "select count(*) as total from posts where USERID='".mysqli_real_escape_string($conn->_connectionID, $_SESSION[USERID])."'"; 
	$executequery=$conn->execute($query);
	$cnt = $executequery->fields['total'];
	if($cnt > 0)
	{
		return "1";
	}
	else
	{
		return "0";
	}
}

function insert_msg_cnt($var)
{
    global $conn;
	$query = "select count(*) as total from inbox where MSGTO='".mysqli_real_escape_string($conn->_connectionID, $_SESSION[USERID])."' AND unread='1'"; 
	$executequery=$conn->execute($query);
	$cnt = $executequery->fields['total'];
	return $cnt;
}

function insert_get_advertisement($var)
{
        global $conn;
        $query="SELECT code FROM advertisements WHERE AID='".mysqli_real_escape_string($conn->_connectionID, $var[AID])."' AND active='1' limit 1";
        $executequery=$conn->execute($query);
        $getad = $executequery->fields[code];
		echo strip_mq_gpc($getad);
}

function verify_email_username($usernametocheck)
{
    global $config,$conn;
	$query = "select count(*) as total from members where username='".mysqli_real_escape_string($conn->_connectionID, $usernametocheck)."' limit 1"; 
	$executequery = $conn->execute($query);
	$totalu = $executequery->fields[total];
	if ($totalu >= 1)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function verify_valid_email($emailtocheck)
{	
	if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $emailtocheck))
	{
		return false;
	}
	else
	{
		return true;
	}

}

function verify_email_unique($emailtocheck)
{
    global $config,$conn;
	$query = "select count(*) as total from members where email='".mysqli_real_escape_string($conn->_connectionID, $emailtocheck)."' limit 1"; 
	$executequery = $conn->execute($query);
	$totalemails = $executequery->fields[total];
	if ($totalemails >= 1)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function mailme($sendto,$sendername,$from,$subject,$sendmailbody,$bcc="")
{
	global $SERVER_NAME,$config;
	$subject = nl2br($subject);
	$sendmailbody = nl2br($sendmailbody);
	$sendto = $sendto;
	if($bcc!="")
	{
		$headers = "Bcc: ".$bcc."\n";
	}
	$headers = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=utf-8 \n";
	$headers .= "X-Priority: 3\n";
	$headers .= "X-MSMail-Priority: Normal\n";
	$headers .= "X-Mailer: PHP/"."MIME-Version: 1.0\n";
	$scriptolutionfull_name = $config['scriptolution_mail_from_name'];
	$headers .= "From: " .  $scriptolutionfull_name.'<'.$from.'>'. "\n";
	$headers .= "Content-Type: text/html\n";
	mail("$sendto","$subject","$sendmailbody","$headers","-f $from");
}

function get_cat_seo($cid)
{
        global $conn;
        $query="SELECT seo FROM categories WHERE CATID='".mysqli_real_escape_string($conn->_connectionID, $cid)."' limit 1";
        $executequery=$conn->execute($query);
        $seo = $executequery->fields['seo'];
		return $seo;
}

function get_cat($cid)
{
        global $conn;
        $query="SELECT name FROM categories WHERE CATID='".mysqli_real_escape_string($conn->_connectionID, $cid)."' limit 1";
        $executequery=$conn->execute($query);
        $name = $executequery->fields[name];
		return $name;
}

function insert_get_cat($var)
{
        global $conn;
        $query="SELECT name FROM categories WHERE CATID='".mysqli_real_escape_string($conn->_connectionID, $var[CATID])."' limit 1";
        $executequery=$conn->execute($query);
        $name = $executequery->fields[name];
		echo $name;
}

function insert_get_stripped_phrase($a)
{
	$stripper = $a[details]; 
	$stripper = str_replace("\\n", "<br>", $stripper);
	$stripper = str_replace("\\r", "", $stripper);
	$stripper = str_replace("\\", "", $stripper);
	return $stripper;
}

function insert_get_stripped_phrase2($a)
{
	$stripper = $a[details]; 
	$stripper = str_replace("\\n", "\n", $stripper);
	$stripper = str_replace("\\r", "\r", $stripper);
	return $stripper;
}

function listdays($selected)
{
	$days = "";
    for($i=1;$i<=31;$i++)
    {
    	if($i == $selected)
		{
        	$days .= "<option value=\"$i\" selected>$i</option>";
        }
        else
		{
        	$days .= "<option value=\"$i\">$i</option>";
        }
    }
    return $days;
}

function listmonths($selected)
{
    $months = "";
    $allmonths = array("","January","February","March","April","May","June","July","August","September","October","November","December");
    for($i=1;$i<=12;$i++)
    {
        if($i == $selected)
		{
        	$months .= "<option value=\"$i\" selected>$allmonths[$i]</option>";
        }
        else
		{
        	$months .= "<option value=\"$i\">$allmonths[$i]</option>";
        }
    }
    return $months;
}

function listyears($selected)
{
        $years = "";
        $thisyear = date("Y");
        for($i=$thisyear-100+13;$i<=$thisyear-13;$i++)
        {
                if($i == $selected)
                        $years .= "<option value=\"$i\" selected>$i</option>";
                else
                        $years .= "<option value=\"$i\">$i</option>";
        }
        return $years;
}

function listcountries($selected)
{
    $country="";
    $countries = array("Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antartica","Antigua and Barbuda","Argentina","Armenia","Aruba","Ascension Island","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Botswana","Bouvet Island","Brazil","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde Islands","Cayman Islands","Chad","Chile","China","Christmas Island","Colombia","Comoros","Cook Islands","Costa Rica","Cote d Ivoire","Croatia/Hrvatska","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guernsey","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Ireland","Isle of Man","Israel","Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte Island", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn Island", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion Island", "Romania", "Russian Federation", "Rwanda", "Saint Helena", "Saint Lucia", "San Marino", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovak Republic", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "Spain", "Sri Lanka", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tokelau", "Tonga Islands", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Western Sahara", "Western Samoa", "Yemen", "Yugoslavia", "Zambia","Zimbabwe");
    for($i=0;$i<count($countries);$i++)
    {
    	if($selected == $countries[$i])
        {
			$country .="<option value=\"$countries[$i]\" selected>$countries[$i]</option>";
		}
        else
		{
			$country .="<option value=\"$countries[$i]\">$countries[$i]</option>";
		}
	}
    return $country;
}

function insert_get_member_profilepicture($var)
{
        global $conn;
        $query="SELECT profilepicture FROM members WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $var[USERID])."' limit 1";
        $executequery=$conn->execute($query);
		$results = $executequery->fields[profilepicture];
		if ($results == "")
			return "noprofilepicture.gif";
		else
			return $results;
}

function insert_com_count($var)
{
    global $conn;
	$query="SELECT count(*) as total FROM posts_comments WHERE PID='".intval($var[PID])."'";
	$executequery=$conn->execute($query);
	$total = $executequery->fields[total];
	return intval($total);
}

function does_post_exist($a)
{
	global $conn, $config;
    $query="SELECT USERID FROM posts WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $a)."'";
    $executequery=$conn->execute($query);
    if ($executequery->recordcount()>0)
        return true;
    else
		return false;
}

function update_last_viewed($a)
{
        global $conn;
		$query = "UPDATE posts SET last_viewed='".time()."' WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $a)."'";
        $executequery=$conn->execute($query);
}

function session_verification()
{
    if ($_SESSION[USERID] != "")
	{
		if (is_numeric($_SESSION[USERID]))
		{
        	return true;
		}
    }
	else
		return false;
}
function insert_get_username_from_userid($var)
{
        global $conn;
        $query="SELECT username FROM members WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $var[USERID])."'";
        $executequery=$conn->execute($query);
        $getusername = $executequery->fields[username];
		return "$getusername";
}

function does_profile_exist($a)
{
	global $conn;
    global $config;
    $query="SELECT username FROM members WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $a)."'";
    $executequery=$conn->execute($query);
    if ($executequery->recordcount()>0)
        return true;
    else
		return false;
}

function update_viewcount_profile($a)
{
        global $conn;
		$query = "UPDATE members SET profileviews = profileviews + 1 WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $a)."'";
        $executequery=$conn->execute($query);
}

function update_viewcount($a)
{
        global $conn;
		$query = "UPDATE posts SET viewcount = viewcount + 1 WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $a)."'";
        $executequery=$conn->execute($query);
}

function insert_get_member_comments_count($var)
{
        global $conn;
        $query="SELECT count(*) as total FROM posts_comments WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $var[USERID])."'";
        $executequery=$conn->execute($query);
        $results = $executequery->fields[total];
		echo "$results";
}

function insert_get_posts_count($var)
{
        global $conn;
        $query="SELECT count(*) as total FROM posts WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $var[USERID])."'";
        $executequery=$conn->execute($query);
        $results = $executequery->fields[total];
		echo "$results";
}

function insert_get_static($var)
{
        global $conn;
        $query="SELECT $var[sel] FROM static WHERE ID='".mysqli_real_escape_string($conn->_connectionID, $var[ID])."'";
        $executequery=$conn->execute($query);
        $returnme = $executequery->fields[$var[sel]];
		$returnme = strip_mq_gpc($returnme);
		echo "$returnme";
}

function insert_strip_special($a)
{
	$text = $a['text'];
	$text = str_replace(",", " ", $text);
	$text = str_replace(".", " ", $text);
	$text=nl2br($text);
	$text = str_replace("\n", " ", $text);
	$text = str_replace("\r", " ", $text);
	$text = str_replace("<br />", " ", $text);
	$text = str_replace("  ", " ", $text);
	$clean = preg_replace("/^[^a-z0-9]?(.*?)[^a-z0-9]?$/i", "$1", $text);
	return $clean;
}

function insert_clickable_link($var)
{
	$text = $var['text'];
	$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $text);
	$ret = ' ' . $text;
	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
	$ret = substr($ret, 1);
	return $ret;
}

function firstDayOfMonth2($uts=null)
{
    $today = is_null($uts) ? getDate() : getDate($uts);
    $first_day = getdate(mktime(0,0,0,$today['mon'],1,$today['year']));
    return $first_day[0];
} 

function firstDayOfYear2($uts=null)
{
    $today = is_null($uts) ? getDate() : getDate($uts);
    $first_day = getdate(mktime(0,0,0,1,1,$today['year']));
    return $first_day[0];
}

function cleanit($text)
{
	return htmlentities(strip_tags(stripslashes($text)), ENT_COMPAT, "UTF-8");
}

function do_resize_image($file, $width = 0, $height = 0, $proportional = false, $output = 'file')
{
	if($height <= 0 && $width <= 0)
	{
	  return false;
	}
	
	$info = getimagesize($file);
	$image = '';

	$final_width = 0;
	$final_height = 0;
	list($width_old, $height_old) = $info;

	if($proportional) 
	{
	  if ($width == 0) $factor = $height/$height_old;
	  elseif ($height == 0) $factor = $width/$width_old;
	  else $factor = min ( $width / $width_old, $height / $height_old);   
	  
	  $final_width = round ($width_old * $factor);
	  $final_height = round ($height_old * $factor);
		  
	  if($final_width > $width_old && $final_height > $height_old)
	  {
	  	  $final_width = $width_old;
		  $final_height = $height_old;

	  }
	}
	else 
	{
	  $final_width = ( $width <= 0 ) ? $width_old : $width;
	  $final_height = ( $height <= 0 ) ? $height_old : $height;
	}
	
	switch($info[2]) 
	{
	  case IMAGETYPE_GIF:
		$image = imagecreatefromgif($file);
	  break;
	  case IMAGETYPE_JPEG:
		$image = imagecreatefromjpeg($file);
	  break;
	  case IMAGETYPE_PNG:
		$image = imagecreatefrompng($file);
	  break;
	  default:
		return false;
	}

	$image_resized = imagecreatetruecolor( $final_width, $final_height );

	if(($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG))
	{
	  $trnprt_indx = imagecolortransparent($image);
	
	  if($trnprt_indx >= 0)
	  {
		$trnprt_color    = imagecolorsforindex($image, $trnprt_indx);
		$trnprt_indx    = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
		imagefill($image_resized, 0, 0, $trnprt_indx);
		imagecolortransparent($image_resized, $trnprt_indx);	
	  } 
	  elseif($info[2] == IMAGETYPE_PNG) 
	  {
		imagealphablending($image_resized, false);
		$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
		imagefill($image_resized, 0, 0, $color);
		imagesavealpha($image_resized, true);
	  }
	}
	imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);

	switch( strtolower($output))
	{
	  case 'browser':
		$mime = image_type_to_mime_type($info[2]);
		header("Content-type: $mime");
		$output = NULL;
	  break;
	  case 'file':
		$output = $file;
	  break;
	  case 'return':
		return $image_resized;
	  break;
	  default:
	  break;
	}
	
	if(file_exists($output))
	{
		@unlink($output);
	}

	switch($info[2])
	{
	  case IMAGETYPE_GIF:
		imagegif($image_resized, $output);
	  break;
	  case IMAGETYPE_JPEG:
		imagejpeg($image_resized, $output, 100);
	  break;
	  case IMAGETYPE_PNG:
		imagepng($image_resized, $output);
	  break;
	  default:
		return false;
	}
	return true;
}

function insert_seo_clean_titles($a)
{
	$title2 = explode(" ", $a['title']);
	$i = 0;
	foreach($title2 as $line)
	{
		if($i < 15)
		{
			$title .= $line."-";
			$i++;
		}
	}
    $title = str_replace(array(":", ".", "^", "*", ",", ";", "~", "[", "]", "<", ">", "\\", "/", "=", "+", "%"),"", $title);
	$last = substr($title, -1);
	if($last == "-")
	{
		$title = substr($title, 0, -1);
	}
	$title = str_replace(" ", "-", $title);
	return $title;
}

function seo_clean_titles($a)
{
	$title2 = explode(" ", $a);
	$i = 0;
	foreach($title2 as $line)
	{
		if($i < 15)
		{
			$title .= $line."-";
			$i++;
		}
	}
    $title = str_replace(array(":", ".", "^", "*", ",", ";", "~", "[", "]", "<", ">", "\\", "/", "=", "+", "%"),"", $title);
	$last = substr($title, -1);
	if($last == "-")
	{
		$title = substr($title, 0, -1);
	}
	$title = str_replace(" ", "-", $title);
	return $title;
}

function delete_gig($PID)
{
	global $config, $conn;
	$PID = intval($PID);
	if($PID > 0)
	{		
		$query = "select p1,p2,p3 from posts WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $PID)."' AND USERID='".mysqli_real_escape_string($conn->_connectionID, $_SESSION['USERID'])."'"; 
		$results = $conn->execute($query);
		$p1=$results->fields['p1'];
		$p2=$results->fields['p2'];
		$p3=$results->fields['p3'];
		if($p1 != "")
		{
			$dp1 = $config['pdir']."/".$p1;
			@chmod($dp1, 0777);
			if (file_exists($dp1))
			{
				@unlink($dp1);
			}
			$dp1 = $config['pdir']."/t/".$p1;
			@chmod($dp1, 0777);
			if (file_exists($dp1))
			{
				@unlink($dp1);
			}
			$dp1 = $config['pdir']."/t2/".$p1;
			@chmod($dp1, 0777);
			if (file_exists($dp1))
			{
				@unlink($dp1);
			}
			$dp1 = $config['pdir']."/t3/".$p1;
			@chmod($dp1, 0777);
			if (file_exists($dp1))
			{
				@unlink($dp1);
			}
			$dp1 = $config['pdir']."/t4/".$p1;
			@chmod($dp1, 0777);
			if (file_exists($dp1))
			{
				@unlink($dp1);
			}
		}
		if($p2 != "")
		{
			$dp2 = $config['pdir']."/".$p2;
			@chmod($dp2, 0777);
			if (file_exists($dp2))
			{
				@unlink($dp2);
			}
			$dp2 = $config['pdir']."/t/".$p2;
			@chmod($dp2, 0777);
			if (file_exists($dp2))
			{
				@unlink($dp2);
			}
			$dp2 = $config['pdir']."/t2/".$p2;
			@chmod($dp2, 0777);
			if (file_exists($dp2))
			{
				@unlink($dp2);
			}
			$dp2 = $config['pdir']."/t3/".$p2;
			@chmod($dp2, 0777);
			if (file_exists($dp2))
			{
				@unlink($dp2);
			}
			$dp2 = $config['pdir']."/t4/".$p2;
			@chmod($dp2, 0777);
			if (file_exists($dp2))
			{
				@unlink($dp2);
			}
		}
		if($p3 != "")
		{
			$dp3 = $config['pdir']."/".$p3;
			@chmod($dp3, 0777);
			if (file_exists($dp3))
			{
				@unlink($dp3);
			}
			$dp3 = $config['pdir']."/t/".$p3;
			@chmod($dp3, 0777);
			if (file_exists($dp3))
			{
				@unlink($dp3);
			}
			$dp3 = $config['pdir']."/t2/".$p3;
			@chmod($dp3, 0777);
			if (file_exists($dp3))
			{
				@unlink($dp3);
			}
			$dp3 = $config['pdir']."/t3/".$p3;
			@chmod($dp3, 0777);
			if (file_exists($dp3))
			{
				@unlink($dp3);
			}
			$dp3 = $config['pdir']."/t4/".$p3;
			@chmod($dp3, 0777);
			if (file_exists($dp3))
			{
				@unlink($dp3);
			}
		}

		$query = "DELETE FROM posts WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $PID)."' AND USERID='".mysqli_real_escape_string($conn->_connectionID, $_SESSION['USERID'])."'";
		$conn->Execute($query);		
	}
}

function delete_gig_admin($PID)
{
	global $config, $conn;
	$PID = intval($PID);
	if($PID > 0)
	{		
		$query = "select p1,p2,p3 from posts WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $PID)."'"; 
		$results = $conn->execute($query);
		$p1=$results->fields['p1'];
		$p2=$results->fields['p2'];
		$p3=$results->fields['p3'];
		if($p1 != "")
		{
			$dp1 = $config['pdir']."/".$p1;
			@chmod($dp1, 0777);
			if (file_exists($dp1))
			{
				@unlink($dp1);
			}
			$dp1 = $config['pdir']."/t/".$p1;
			@chmod($dp1, 0777);
			if (file_exists($dp1))
			{
				@unlink($dp1);
			}
			$dp1 = $config['pdir']."/t2/".$p1;
			@chmod($dp1, 0777);
			if (file_exists($dp1))
			{
				@unlink($dp1);
			}
			$dp1 = $config['pdir']."/t3/".$p1;
			@chmod($dp1, 0777);
			if (file_exists($dp1))
			{
				@unlink($dp1);
			}
			$dp1 = $config['pdir']."/t4/".$p1;
			@chmod($dp1, 0777);
			if (file_exists($dp1))
			{
				@unlink($dp1);
			}
		}
		if($p2 != "")
		{
			$dp2 = $config['pdir']."/".$p2;
			@chmod($dp2, 0777);
			if (file_exists($dp2))
			{
				@unlink($dp2);
			}
			$dp2 = $config['pdir']."/t/".$p2;
			@chmod($dp2, 0777);
			if (file_exists($dp2))
			{
				@unlink($dp2);
			}
			$dp2 = $config['pdir']."/t2/".$p2;
			@chmod($dp2, 0777);
			if (file_exists($dp2))
			{
				@unlink($dp2);
			}
			$dp2 = $config['pdir']."/t3/".$p2;
			@chmod($dp2, 0777);
			if (file_exists($dp2))
			{
				@unlink($dp2);
			}
			$dp2 = $config['pdir']."/t4/".$p2;
			@chmod($dp2, 0777);
			if (file_exists($dp2))
			{
				@unlink($dp2);
			}
		}
		if($p3 != "")
		{
			$dp3 = $config['pdir']."/".$p3;
			@chmod($dp3, 0777);
			if (file_exists($dp3))
			{
				@unlink($dp3);
			}
			$dp3 = $config['pdir']."/t/".$p3;
			@chmod($dp3, 0777);
			if (file_exists($dp3))
			{
				@unlink($dp3);
			}
			$dp3 = $config['pdir']."/t2/".$p3;
			@chmod($dp3, 0777);
			if (file_exists($dp3))
			{
				@unlink($dp3);
			}
			$dp3 = $config['pdir']."/t3/".$p3;
			@chmod($dp3, 0777);
			if (file_exists($dp3))
			{
				@unlink($dp3);
			}
			$dp3 = $config['pdir']."/t4/".$p3;
			@chmod($dp3, 0777);
			if (file_exists($dp3))
			{
				@unlink($dp3);
			}
		}

		//
		$query = "DELETE FROM bookmarks WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $PID)."'";
		$conn->Execute($query);
		$query = "DELETE FROM offerscriptolution WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $PID)."'";
		$conn->Execute($query);	
		//
		$query = "DELETE FROM posts WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $PID)."'";
		$conn->Execute($query);		
	}
}

function issue_refund($buyer,$OID,$rprice)
{
    global $conn;
	if($buyer > 0 && $OID > 0 && $rprice > 0)
	{
		$query = "select status, price from orders where OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."' AND USERID='".mysqli_real_escape_string($conn->_connectionID, $buyer)."'"; 
		$executequery=$conn->execute($query);
		$status = $executequery->fields['status'];
		$price = $executequery->fields['price'];
		$rprice = $price;
		if($price > 0)
		{
			if($status != "3" && $status != "5" && $status != "7")
			{
				$query = "INSERT INTO payments SET USERID='".mysqli_real_escape_string($conn->_connectionID, $buyer)."', OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', time='".time()."', price='".mysqli_real_escape_string($conn->_connectionID, $rprice)."', t='0'"; 
				$executequery=$conn->execute($query);
				$transid = mysqli_insert_id($conn->_connectionID);
				if($transid > 0)
				{
					$query = "UPDATE payments SET cancel='1' WHERE OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."' AND t='1' AND cancel='0' limit 1"; 
					$executequery=$conn->execute($query);
					
					$query = "UPDATE members SET funds=funds+$rprice WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $buyer)."' limit 1"; 
					$executequery=$conn->execute($query);
				}
			}
		}
	}
}

function insert_get_time_to_days_ago($a)
{
	$td = date("d-m-Y");
	$timestamp = strtotime($td);
	//$currenttime = time();
	//$timediff = $currenttime - $a[time];
	//$oneday = 60 * 60 * 24;
	//$dayspassed = floor($timediff/$oneday);
	//if ($dayspassed == "0")
	if($a[time] > $timestamp)
	{
		return date("g:i",$a[time]);
	}
	else
	{
		return date("M j",$a[time]);
	}
}

function insert_get_deadline($a)
{
	$days = intval($a['days']);
	$time = intval($a['time']);
	$ctime = $days * 24 * 60 * 60;
	$utime = $time + $ctime;
	return date("M j, Y",$utime);
}

function count_days($a, $b)
{
	$gd_a = getdate( $a );
	$gd_b = getdate( $b );
	$a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
	$b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
	return round( abs( $a_new - $b_new ) / 86400 );
}

function insert_countdown($a)
{
	global $lang;
	$days = intval($a['days']);
	$time = intval($a['time']);
	$ctime = $days * 24 * 60 * 60;
	$f_timestamp = $time + $ctime;
	$c_timestamp = time();
	if($f_timestamp > $c_timestamp)
	{
		$days = floor( ($f_timestamp-$c_timestamp)/(60*60*24) );
		$f_timestamp = $f_timestamp - ($days*60*60*24);
		$hours = floor( ($f_timestamp-$c_timestamp)/(60*60) );
		$f_timestamp = $f_timestamp - ($hours*60*60);
		$minutes = floor( ($f_timestamp-$c_timestamp)/(60) );
		$f_timestamp = $f_timestamp - ($minutes*60);
		$seconds = $f_timestamp-$c_timestamp;
		$go = "(<b> ";
		if($days > 0)
		{
			$go .= 	$days." ";
			if($days == "1")
			{
				$go .= 	$lang['281']." ";
			}
			else
			{
				$go .= 	$lang['280']." ";
			}
		}
		if($hours > 0)
		{
			$go .= 	$hours." ";
			if($hours == "1")
			{
				$go .= 	$lang['285']." ";
			}
			else
			{
				$go .= 	$lang['284']." ";
			}
		}
		if($minutes > 0)
		{
			$go .= 	$minutes." ";
			if($minutes == "1")
			{
				$go .= 	$lang['283']." ";
			}
			else
			{
				$go .= 	$lang['282']." ";
			}
		}
		$go .= "</b>)";
		return $go;	
	}
}

function insert_late($a)
{
	$days = intval($a['days']);
	$time = intval($a['time']);
	$ctime = $days * 24 * 60 * 60;
	$utime = $time + $ctime;
	$now = time();
	if($now > $utime)
	{
		return "1";	
	}
	else
	{
		return "0";	
	}
}

function insert_get_days_withdraw($a)
{
	global $config;
	$dbw = intval($config['days_before_withdraw']);
	$n = time();
	$wtime = $dbw * 24 * 60 * 60;
	$t = intval($a['t']) + $wtime;
	if($t > $n)
	{
		return count_days($t, $n);
	}
	else
	{
		return "0";	
	}
}

function get_days_withdraw($a)
{
	global $config;
	$dbw = intval($config['days_before_withdraw']);
	$n = time();
	$wtime = $dbw * 24 * 60 * 60;
	$t = intval($a) + $wtime;
	if($t > $n)
	{
		return count_days($t, $n);
	}
	else
	{
		return "0";	
	}
}

function insert_get_yprice($a)
{
	global $config;
	$p = number_format($a['p'], 2, '.', '');
	$c = number_format($a['c'], 2, '.', '');
	
	if($c == "0")
	{
		$c = number_format($config['commission'], 2, '.', '');
	}
	if($p > $c)
	{
		$pc = $p - $c;
		$formatted = sprintf("%01.2f", $pc);
		return $formatted;
	}
	else
	{
		return "0.00";	
	}
}

function get_yprice($a)
{
	global $config;	
	$c = number_format($config['commission'], 2, '.', '');
	$p = number_format($a, 2, '.', '');
	
	if($p > $c)
	{
		$pc = $p - $c;
		$formatted = sprintf("%01.2f", $pc);
		return $formatted;
	}
	else
	{
		return "0.00";	
	}
}

function get_yprice2($a, $b)
{
	global $config;
	$c = number_format($b, 2, '.', '');
	$p = number_format($a, 2, '.', '');
	if($p > $c)
	{
		$pc = $p - $c;
		$formatted = sprintf("%01.2f", $pc);
		return $formatted;
	}
	else
	{
		return "0.00";	
	}
}

function insert_get_explode($a)
{
	$tags = explode(" ", $a['gtags']);
	return $tags;
}

function send_update_email($msgto, $oid)
{
	if($msgto > 0 && $oid > 0)
	{
		global $config, $conn, $lang;
		$query = "select username,email from members where USERID='".mysqli_real_escape_string($conn->_connectionID, $msgto)."'"; 
		$executequery=$conn->execute($query);
		$sendto = $executequery->fields['email'];
		$sendname = $executequery->fields['username'];
		if($sendto != "")
		{
			$sendername = $config['site_name'];
			$from = $config['site_email'];
			$subject = $lang['407'];
			$sendmailbody = stripslashes($sendname).",<br><br>";
			$sendmailbody .= $lang['408']." ".$lang['409']."<br>";
			$sendmailbody .= "<a href=".$config['baseurl']."/track?id=$oid>".$config['baseurl']."/track?id=$oid</a><br><br>";
			$sendmailbody .= $lang['23'].",<br>".stripslashes($sendername);
			mailme($sendto,$sendername,$from,$subject,$sendmailbody,$bcc="");
		}
	}
}

function cancel_revenue($OID)
{
    global $config,$conn;
	if($OID > 0)
	{
		$query = "select PID from orders where OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."'"; 
		$executequery=$conn->execute($query);
		$PID = $executequery->fields['PID'];
		if($PID > 0)
		{
			$query = "select price from posts where PID='".mysqli_real_escape_string($conn->_connectionID, $PID)."'"; 
			$executequery=$conn->execute($query);
			$price = $executequery->fields['price'];
			if($price > 0)
			{
				$query = "UPDATE posts SET rev=rev-$price WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $PID)."'"; 
				$executequery=$conn->execute($query);
			}
		}
	}
}

function insert_get_short_url($a)
{
    global $conn, $config;
	$SPID = intval($a['PID']);
	$stitle = stripslashes($a['title']);
	$sshort = stripslashes($a['short']);
	$SSEO = stripslashes($a['seo']);
	$SSEO = str_replace(" ", "+", $SSEO);
	$scriptolution_url = $config['baseurl']."/".$SSEO."/".$SPID."/".$stitle;
	if($SPID > 0)
	{
		if($sshort == "")
		{
			$takenurl =  file_get_contents("http://www.taken.to/scriptolution.php?url=".$scriptolution_url);
			if($takenurl != "")
			{
				$sshort = str_replace("http://www.taken.to/", "", $takenurl);
				if($sshort != "")
				{
					$query = "UPDATE posts SET short='".mysqli_real_escape_string($conn->_connectionID, $sshort)."' WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $SPID)."'";
					$conn->execute($query);
					$rme = 	"http://www.taken.to/".$sshort;
				}
				else
				{
					$rme = 	$scriptolution_url;	
				}
			}
			else
			{
				$rme = 	$scriptolution_url;
			}
			
		}
		else
		{
			$rme = 	"http://www.taken.to/".$sshort;
		}
	}
	else
	{
		$rme = 	$scriptolution_url;
	}
	return $rme;
}

function insert_get_redirect($a)
{
	$PID = intval($a['PID']);
	$seo = $a['seo'];
	$gtitle = $a['gtitle'];
	$rme = stripslashes($seo)."/".$PID."/".stripslashes($gtitle);
	return base64_encode($rme);
} 

function insert_get_redirect2($a)
{
    $uname = $a['uname'];
	$pid = $a['pid'];
	if($pid > 0)
	{
		$addp = "?id=".$pid;	
	}
	$rme = "conversations/".stripslashes($uname).$addp;
	return base64_encode($rme);
}

function update_gig_rating($PID)
{
	global $conn;
	$query = "select good, bad from ratings where PID='".mysqli_real_escape_string($conn->_connectionID, $PID)."'"; 
	$results=$conn->execute($query);
	$f = $results->getrows();
	$t = 0;
	$grat = 0;
	$brat = 0;
	for($k=0;$k<count($f);$k++)
	{
		$tgood = $f[$k]['good'];
		$tbad = $f[$k]['bad'];
		if($tgood == "1")
		{
			$grat++;	
		}
		elseif($tbad == "1")
		{
			$brat++;	
		}
	}
	$g = $grat;
	$b = $brat;
	$t = $g + $b;
	if($t > 0)
	{
		$r = (($g / $t) * 100);
		$gr = round($r, 1);
	}
	else
	{
		$gr = 0;
	}
	$uquery = "UPDATE posts SET rating='".$gr."', rcount=rcount+1 WHERE PID='".mysqli_real_escape_string($conn->_connectionID, $PID)."'";
	$conn->execute($uquery);
}

function insert_country_code_to_country($a)
{
	$code = $a['code'];
    $country = '';
    if( $code == 'AF' ) $country = 'Afghanistan';
    if( $code == 'AX' ) $country = 'Aland Islands';
    if( $code == 'AL' ) $country = 'Albania';
    if( $code == 'DZ' ) $country = 'Algeria';
    if( $code == 'AS' ) $country = 'American Samoa';
    if( $code == 'AD' ) $country = 'Andorra';
    if( $code == 'AO' ) $country = 'Angola';
    if( $code == 'AI' ) $country = 'Anguilla';
    if( $code == 'AQ' ) $country = 'Antarctica';
    if( $code == 'AG' ) $country = 'Antigua and Barbuda';
    if( $code == 'AR' ) $country = 'Argentina';
    if( $code == 'AM' ) $country = 'Armenia';
    if( $code == 'AW' ) $country = 'Aruba';
    if( $code == 'AU' ) $country = 'Australia';
    if( $code == 'AT' ) $country = 'Austria';
    if( $code == 'AZ' ) $country = 'Azerbaijan';
    if( $code == 'BS' ) $country = 'Bahamas the';
    if( $code == 'BH' ) $country = 'Bahrain';
    if( $code == 'BD' ) $country = 'Bangladesh';
    if( $code == 'BB' ) $country = 'Barbados';
    if( $code == 'BY' ) $country = 'Belarus';
    if( $code == 'BE' ) $country = 'Belgium';
    if( $code == 'BZ' ) $country = 'Belize';
    if( $code == 'BJ' ) $country = 'Benin';
    if( $code == 'BM' ) $country = 'Bermuda';
    if( $code == 'BT' ) $country = 'Bhutan';
    if( $code == 'BO' ) $country = 'Bolivia';
    if( $code == 'BA' ) $country = 'Bosnia and Herzegovina';
    if( $code == 'BW' ) $country = 'Botswana';
    if( $code == 'BV' ) $country = 'Bouvet Island (Bouvetoya)';
    if( $code == 'BR' ) $country = 'Brazil';
    if( $code == 'IO' ) $country = 'British Indian Ocean Territory (Chagos Archipelago)';
    if( $code == 'VG' ) $country = 'British Virgin Islands';
    if( $code == 'BN' ) $country = 'Brunei Darussalam';
    if( $code == 'BG' ) $country = 'Bulgaria';
    if( $code == 'BF' ) $country = 'Burkina Faso';
    if( $code == 'BI' ) $country = 'Burundi';
    if( $code == 'KH' ) $country = 'Cambodia';
    if( $code == 'CM' ) $country = 'Cameroon';
    if( $code == 'CA' ) $country = 'Canada';
    if( $code == 'CV' ) $country = 'Cape Verde';
    if( $code == 'KY' ) $country = 'Cayman Islands';
    if( $code == 'CF' ) $country = 'Central African Republic';
    if( $code == 'TD' ) $country = 'Chad';
    if( $code == 'CL' ) $country = 'Chile';
    if( $code == 'CN' ) $country = 'China';
    if( $code == 'CX' ) $country = 'Christmas Island';
    if( $code == 'CC' ) $country = 'Cocos (Keeling) Islands';
    if( $code == 'CO' ) $country = 'Colombia';
    if( $code == 'KM' ) $country = 'Comoros the';
    if( $code == 'CD' ) $country = 'Congo';
    if( $code == 'CG' ) $country = 'Congo the';
    if( $code == 'CK' ) $country = 'Cook Islands';
    if( $code == 'CR' ) $country = 'Costa Rica';
    if( $code == 'CI' ) $country = 'Cote d\'Ivoire';
    if( $code == 'HR' ) $country = 'Croatia';
    if( $code == 'CU' ) $country = 'Cuba';
    if( $code == 'CY' ) $country = 'Cyprus';
    if( $code == 'CZ' ) $country = 'Czech Republic';
    if( $code == 'DK' ) $country = 'Denmark';
    if( $code == 'DJ' ) $country = 'Djibouti';
    if( $code == 'DM' ) $country = 'Dominica';
    if( $code == 'DO' ) $country = 'Dominican Republic';
    if( $code == 'EC' ) $country = 'Ecuador';
    if( $code == 'EG' ) $country = 'Egypt';
    if( $code == 'SV' ) $country = 'El Salvador';
    if( $code == 'GQ' ) $country = 'Equatorial Guinea';
    if( $code == 'ER' ) $country = 'Eritrea';
    if( $code == 'EE' ) $country = 'Estonia';
    if( $code == 'ET' ) $country = 'Ethiopia';
    if( $code == 'FO' ) $country = 'Faroe Islands';
    if( $code == 'FK' ) $country = 'Falkland Islands (Malvinas)';
    if( $code == 'FJ' ) $country = 'Fiji the Fiji Islands';
    if( $code == 'FI' ) $country = 'Finland';
    if( $code == 'FR' ) $country = 'France, French Republic';
    if( $code == 'GF' ) $country = 'French Guiana';
    if( $code == 'PF' ) $country = 'French Polynesia';
    if( $code == 'TF' ) $country = 'French Southern Territories';
    if( $code == 'GA' ) $country = 'Gabon';
    if( $code == 'GM' ) $country = 'Gambia the';
    if( $code == 'GE' ) $country = 'Georgia';
    if( $code == 'DE' ) $country = 'Germany';
    if( $code == 'GH' ) $country = 'Ghana';
    if( $code == 'GI' ) $country = 'Gibraltar';
    if( $code == 'GR' ) $country = 'Greece';
    if( $code == 'GL' ) $country = 'Greenland';
    if( $code == 'GD' ) $country = 'Grenada';
    if( $code == 'GP' ) $country = 'Guadeloupe';
    if( $code == 'GU' ) $country = 'Guam';
    if( $code == 'GT' ) $country = 'Guatemala';
    if( $code == 'GG' ) $country = 'Guernsey';
    if( $code == 'GN' ) $country = 'Guinea';
    if( $code == 'GW' ) $country = 'Guinea-Bissau';
    if( $code == 'GY' ) $country = 'Guyana';
    if( $code == 'HT' ) $country = 'Haiti';
    if( $code == 'HM' ) $country = 'Heard Island and McDonald Islands';
    if( $code == 'VA' ) $country = 'Holy See (Vatican City State)';
    if( $code == 'HN' ) $country = 'Honduras';
    if( $code == 'HK' ) $country = 'Hong Kong';
    if( $code == 'HU' ) $country = 'Hungary';
    if( $code == 'IS' ) $country = 'Iceland';
    if( $code == 'IN' ) $country = 'India';
    if( $code == 'ID' ) $country = 'Indonesia';
    if( $code == 'IR' ) $country = 'Iran';
    if( $code == 'IQ' ) $country = 'Iraq';
    if( $code == 'IE' ) $country = 'Ireland';
    if( $code == 'IM' ) $country = 'Isle of Man';
    if( $code == 'IL' ) $country = 'Israel';
    if( $code == 'IT' ) $country = 'Italy';
    if( $code == 'JM' ) $country = 'Jamaica';
    if( $code == 'JP' ) $country = 'Japan';
    if( $code == 'JE' ) $country = 'Jersey';
    if( $code == 'JO' ) $country = 'Jordan';
    if( $code == 'KZ' ) $country = 'Kazakhstan';
    if( $code == 'KE' ) $country = 'Kenya';
    if( $code == 'KI' ) $country = 'Kiribati';
    if( $code == 'KP' ) $country = 'Korea';
    if( $code == 'KR' ) $country = 'Korea';
    if( $code == 'KW' ) $country = 'Kuwait';
    if( $code == 'KG' ) $country = 'Kyrgyz Republic';
    if( $code == 'LA' ) $country = 'Lao';
    if( $code == 'LV' ) $country = 'Latvia';
    if( $code == 'LB' ) $country = 'Lebanon';
    if( $code == 'LS' ) $country = 'Lesotho';
    if( $code == 'LR' ) $country = 'Liberia';
    if( $code == 'LY' ) $country = 'Libyan Arab Jamahiriya';
    if( $code == 'LI' ) $country = 'Liechtenstein';
    if( $code == 'LT' ) $country = 'Lithuania';
    if( $code == 'LU' ) $country = 'Luxembourg';
    if( $code == 'MO' ) $country = 'Macao';
    if( $code == 'MK' ) $country = 'Macedonia';
    if( $code == 'MG' ) $country = 'Madagascar';
    if( $code == 'MW' ) $country = 'Malawi';
    if( $code == 'MY' ) $country = 'Malaysia';
    if( $code == 'MV' ) $country = 'Maldives';
    if( $code == 'ML' ) $country = 'Mali';
    if( $code == 'MT' ) $country = 'Malta';
    if( $code == 'MH' ) $country = 'Marshall Islands';
    if( $code == 'MQ' ) $country = 'Martinique';
    if( $code == 'MR' ) $country = 'Mauritania';
    if( $code == 'MU' ) $country = 'Mauritius';
    if( $code == 'YT' ) $country = 'Mayotte';
    if( $code == 'MX' ) $country = 'Mexico';
    if( $code == 'FM' ) $country = 'Micronesia';
    if( $code == 'MD' ) $country = 'Moldova';
    if( $code == 'MC' ) $country = 'Monaco';
    if( $code == 'MN' ) $country = 'Mongolia';
    if( $code == 'ME' ) $country = 'Montenegro';
    if( $code == 'MS' ) $country = 'Montserrat';
    if( $code == 'MA' ) $country = 'Morocco';
    if( $code == 'MZ' ) $country = 'Mozambique';
    if( $code == 'MM' ) $country = 'Myanmar';
    if( $code == 'NA' ) $country = 'Namibia';
    if( $code == 'NR' ) $country = 'Nauru';
    if( $code == 'NP' ) $country = 'Nepal';
    if( $code == 'AN' ) $country = 'Netherlands Antilles';
    if( $code == 'NL' ) $country = 'Netherlands the';
    if( $code == 'NC' ) $country = 'New Caledonia';
    if( $code == 'NZ' ) $country = 'New Zealand';
    if( $code == 'NI' ) $country = 'Nicaragua';
    if( $code == 'NE' ) $country = 'Niger';
    if( $code == 'NG' ) $country = 'Nigeria';
    if( $code == 'NU' ) $country = 'Niue';
    if( $code == 'NF' ) $country = 'Norfolk Island';
    if( $code == 'MP' ) $country = 'Northern Mariana Islands';
    if( $code == 'NO' ) $country = 'Norway';
    if( $code == 'OM' ) $country = 'Oman';
    if( $code == 'PK' ) $country = 'Pakistan';
    if( $code == 'PW' ) $country = 'Palau';
    if( $code == 'PS' ) $country = 'Palestinian Territory';
    if( $code == 'PA' ) $country = 'Panama';
    if( $code == 'PG' ) $country = 'Papua New Guinea';
    if( $code == 'PY' ) $country = 'Paraguay';
    if( $code == 'PE' ) $country = 'Peru';
    if( $code == 'PH' ) $country = 'Philippines';
    if( $code == 'PN' ) $country = 'Pitcairn Islands';
    if( $code == 'PL' ) $country = 'Poland';
    if( $code == 'PT' ) $country = 'Portugal, Portuguese Republic';
    if( $code == 'PR' ) $country = 'Puerto Rico';
    if( $code == 'QA' ) $country = 'Qatar';
    if( $code == 'RE' ) $country = 'Reunion';
    if( $code == 'RO' ) $country = 'Romania';
    if( $code == 'RU' ) $country = 'Russian Federation';
    if( $code == 'RW' ) $country = 'Rwanda';
    if( $code == 'BL' ) $country = 'Saint Barthelemy';
    if( $code == 'SH' ) $country = 'Saint Helena';
    if( $code == 'KN' ) $country = 'Saint Kitts and Nevis';
    if( $code == 'LC' ) $country = 'Saint Lucia';
    if( $code == 'MF' ) $country = 'Saint Martin';
    if( $code == 'PM' ) $country = 'Saint Pierre and Miquelon';
    if( $code == 'VC' ) $country = 'Saint Vincent and the Grenadines';
    if( $code == 'WS' ) $country = 'Samoa';
    if( $code == 'SM' ) $country = 'San Marino';
    if( $code == 'ST' ) $country = 'Sao Tome and Principe';
    if( $code == 'SA' ) $country = 'Saudi Arabia';
    if( $code == 'SN' ) $country = 'Senegal';
    if( $code == 'RS' ) $country = 'Serbia';
    if( $code == 'SC' ) $country = 'Seychelles';
    if( $code == 'SL' ) $country = 'Sierra Leone';
    if( $code == 'SG' ) $country = 'Singapore';
    if( $code == 'SK' ) $country = 'Slovakia (Slovak Republic)';
    if( $code == 'SI' ) $country = 'Slovenia';
    if( $code == 'SB' ) $country = 'Solomon Islands';
    if( $code == 'SO' ) $country = 'Somalia, Somali Republic';
    if( $code == 'ZA' ) $country = 'South Africa';
    if( $code == 'GS' ) $country = 'South Georgia and the South Sandwich Islands';
    if( $code == 'ES' ) $country = 'Spain';
    if( $code == 'LK' ) $country = 'Sri Lanka';
    if( $code == 'SD' ) $country = 'Sudan';
    if( $code == 'SR' ) $country = 'Suriname';
    if( $code == 'SJ' ) $country = 'Svalbard & Jan Mayen Islands';
    if( $code == 'SZ' ) $country = 'Swaziland';
    if( $code == 'SE' ) $country = 'Sweden';
    if( $code == 'CH' ) $country = 'Switzerland, Swiss Confederation';
    if( $code == 'SY' ) $country = 'Syrian Arab Republic';
    if( $code == 'TW' ) $country = 'Taiwan';
    if( $code == 'TJ' ) $country = 'Tajikistan';
    if( $code == 'TZ' ) $country = 'Tanzania';
    if( $code == 'TH' ) $country = 'Thailand';
    if( $code == 'TL' ) $country = 'Timor-Leste';
    if( $code == 'TG' ) $country = 'Togo';
    if( $code == 'TK' ) $country = 'Tokelau';
    if( $code == 'TO' ) $country = 'Tonga';
    if( $code == 'TT' ) $country = 'Trinidad and Tobago';
    if( $code == 'TN' ) $country = 'Tunisia';
    if( $code == 'TR' ) $country = 'Turkey';
    if( $code == 'TM' ) $country = 'Turkmenistan';
    if( $code == 'TC' ) $country = 'Turks and Caicos Islands';
    if( $code == 'TV' ) $country = 'Tuvalu';
    if( $code == 'UG' ) $country = 'Uganda';
    if( $code == 'UA' ) $country = 'Ukraine';
    if( $code == 'AE' ) $country = 'United Arab Emirates';
    if( $code == 'GB' ) $country = 'United Kingdom';
    if( $code == 'US' ) $country = 'United States of America';
    if( $code == 'UM' ) $country = 'United States Minor Outlying Islands';
    if( $code == 'VI' ) $country = 'United States Virgin Islands';
    if( $code == 'UY' ) $country = 'Uruguay, Eastern Republic of';
    if( $code == 'UZ' ) $country = 'Uzbekistan';
    if( $code == 'VU' ) $country = 'Vanuatu';
    if( $code == 'VE' ) $country = 'Venezuela';
    if( $code == 'VN' ) $country = 'Vietnam';
    if( $code == 'WF' ) $country = 'Wallis and Futuna';
    if( $code == 'EH' ) $country = 'Western Sahara';
    if( $code == 'YE' ) $country = 'Yemen';
    if( $code == 'ZM' ) $country = 'Zambia';
    if( $code == 'ZW' ) $country = 'Zimbabwe';
    if( $country == '') $country = $code;
    return $country;
}

function country_code_to_country($code)
{
    $country = '';
    if( $code == 'AF' ) $country = 'Afghanistan';
    if( $code == 'AX' ) $country = 'Aland Islands';
    if( $code == 'AL' ) $country = 'Albania';
    if( $code == 'DZ' ) $country = 'Algeria';
    if( $code == 'AS' ) $country = 'American Samoa';
    if( $code == 'AD' ) $country = 'Andorra';
    if( $code == 'AO' ) $country = 'Angola';
    if( $code == 'AI' ) $country = 'Anguilla';
    if( $code == 'AQ' ) $country = 'Antarctica';
    if( $code == 'AG' ) $country = 'Antigua and Barbuda';
    if( $code == 'AR' ) $country = 'Argentina';
    if( $code == 'AM' ) $country = 'Armenia';
    if( $code == 'AW' ) $country = 'Aruba';
    if( $code == 'AU' ) $country = 'Australia';
    if( $code == 'AT' ) $country = 'Austria';
    if( $code == 'AZ' ) $country = 'Azerbaijan';
    if( $code == 'BS' ) $country = 'Bahamas the';
    if( $code == 'BH' ) $country = 'Bahrain';
    if( $code == 'BD' ) $country = 'Bangladesh';
    if( $code == 'BB' ) $country = 'Barbados';
    if( $code == 'BY' ) $country = 'Belarus';
    if( $code == 'BE' ) $country = 'Belgium';
    if( $code == 'BZ' ) $country = 'Belize';
    if( $code == 'BJ' ) $country = 'Benin';
    if( $code == 'BM' ) $country = 'Bermuda';
    if( $code == 'BT' ) $country = 'Bhutan';
    if( $code == 'BO' ) $country = 'Bolivia';
    if( $code == 'BA' ) $country = 'Bosnia and Herzegovina';
    if( $code == 'BW' ) $country = 'Botswana';
    if( $code == 'BV' ) $country = 'Bouvet Island (Bouvetoya)';
    if( $code == 'BR' ) $country = 'Brazil';
    if( $code == 'IO' ) $country = 'British Indian Ocean Territory (Chagos Archipelago)';
    if( $code == 'VG' ) $country = 'British Virgin Islands';
    if( $code == 'BN' ) $country = 'Brunei Darussalam';
    if( $code == 'BG' ) $country = 'Bulgaria';
    if( $code == 'BF' ) $country = 'Burkina Faso';
    if( $code == 'BI' ) $country = 'Burundi';
    if( $code == 'KH' ) $country = 'Cambodia';
    if( $code == 'CM' ) $country = 'Cameroon';
    if( $code == 'CA' ) $country = 'Canada';
    if( $code == 'CV' ) $country = 'Cape Verde';
    if( $code == 'KY' ) $country = 'Cayman Islands';
    if( $code == 'CF' ) $country = 'Central African Republic';
    if( $code == 'TD' ) $country = 'Chad';
    if( $code == 'CL' ) $country = 'Chile';
    if( $code == 'CN' ) $country = 'China';
    if( $code == 'CX' ) $country = 'Christmas Island';
    if( $code == 'CC' ) $country = 'Cocos (Keeling) Islands';
    if( $code == 'CO' ) $country = 'Colombia';
    if( $code == 'KM' ) $country = 'Comoros the';
    if( $code == 'CD' ) $country = 'Congo';
    if( $code == 'CG' ) $country = 'Congo the';
    if( $code == 'CK' ) $country = 'Cook Islands';
    if( $code == 'CR' ) $country = 'Costa Rica';
    if( $code == 'CI' ) $country = 'Cote d\'Ivoire';
    if( $code == 'HR' ) $country = 'Croatia';
    if( $code == 'CU' ) $country = 'Cuba';
    if( $code == 'CY' ) $country = 'Cyprus';
    if( $code == 'CZ' ) $country = 'Czech Republic';
    if( $code == 'DK' ) $country = 'Denmark';
    if( $code == 'DJ' ) $country = 'Djibouti';
    if( $code == 'DM' ) $country = 'Dominica';
    if( $code == 'DO' ) $country = 'Dominican Republic';
    if( $code == 'EC' ) $country = 'Ecuador';
    if( $code == 'EG' ) $country = 'Egypt';
    if( $code == 'SV' ) $country = 'El Salvador';
    if( $code == 'GQ' ) $country = 'Equatorial Guinea';
    if( $code == 'ER' ) $country = 'Eritrea';
    if( $code == 'EE' ) $country = 'Estonia';
    if( $code == 'ET' ) $country = 'Ethiopia';
    if( $code == 'FO' ) $country = 'Faroe Islands';
    if( $code == 'FK' ) $country = 'Falkland Islands (Malvinas)';
    if( $code == 'FJ' ) $country = 'Fiji the Fiji Islands';
    if( $code == 'FI' ) $country = 'Finland';
    if( $code == 'FR' ) $country = 'France, French Republic';
    if( $code == 'GF' ) $country = 'French Guiana';
    if( $code == 'PF' ) $country = 'French Polynesia';
    if( $code == 'TF' ) $country = 'French Southern Territories';
    if( $code == 'GA' ) $country = 'Gabon';
    if( $code == 'GM' ) $country = 'Gambia the';
    if( $code == 'GE' ) $country = 'Georgia';
    if( $code == 'DE' ) $country = 'Germany';
    if( $code == 'GH' ) $country = 'Ghana';
    if( $code == 'GI' ) $country = 'Gibraltar';
    if( $code == 'GR' ) $country = 'Greece';
    if( $code == 'GL' ) $country = 'Greenland';
    if( $code == 'GD' ) $country = 'Grenada';
    if( $code == 'GP' ) $country = 'Guadeloupe';
    if( $code == 'GU' ) $country = 'Guam';
    if( $code == 'GT' ) $country = 'Guatemala';
    if( $code == 'GG' ) $country = 'Guernsey';
    if( $code == 'GN' ) $country = 'Guinea';
    if( $code == 'GW' ) $country = 'Guinea-Bissau';
    if( $code == 'GY' ) $country = 'Guyana';
    if( $code == 'HT' ) $country = 'Haiti';
    if( $code == 'HM' ) $country = 'Heard Island and McDonald Islands';
    if( $code == 'VA' ) $country = 'Holy See (Vatican City State)';
    if( $code == 'HN' ) $country = 'Honduras';
    if( $code == 'HK' ) $country = 'Hong Kong';
    if( $code == 'HU' ) $country = 'Hungary';
    if( $code == 'IS' ) $country = 'Iceland';
    if( $code == 'IN' ) $country = 'India';
    if( $code == 'ID' ) $country = 'Indonesia';
    if( $code == 'IR' ) $country = 'Iran';
    if( $code == 'IQ' ) $country = 'Iraq';
    if( $code == 'IE' ) $country = 'Ireland';
    if( $code == 'IM' ) $country = 'Isle of Man';
    if( $code == 'IL' ) $country = 'Israel';
    if( $code == 'IT' ) $country = 'Italy';
    if( $code == 'JM' ) $country = 'Jamaica';
    if( $code == 'JP' ) $country = 'Japan';
    if( $code == 'JE' ) $country = 'Jersey';
    if( $code == 'JO' ) $country = 'Jordan';
    if( $code == 'KZ' ) $country = 'Kazakhstan';
    if( $code == 'KE' ) $country = 'Kenya';
    if( $code == 'KI' ) $country = 'Kiribati';
    if( $code == 'KP' ) $country = 'Korea';
    if( $code == 'KR' ) $country = 'Korea';
    if( $code == 'KW' ) $country = 'Kuwait';
    if( $code == 'KG' ) $country = 'Kyrgyz Republic';
    if( $code == 'LA' ) $country = 'Lao';
    if( $code == 'LV' ) $country = 'Latvia';
    if( $code == 'LB' ) $country = 'Lebanon';
    if( $code == 'LS' ) $country = 'Lesotho';
    if( $code == 'LR' ) $country = 'Liberia';
    if( $code == 'LY' ) $country = 'Libyan Arab Jamahiriya';
    if( $code == 'LI' ) $country = 'Liechtenstein';
    if( $code == 'LT' ) $country = 'Lithuania';
    if( $code == 'LU' ) $country = 'Luxembourg';
    if( $code == 'MO' ) $country = 'Macao';
    if( $code == 'MK' ) $country = 'Macedonia';
    if( $code == 'MG' ) $country = 'Madagascar';
    if( $code == 'MW' ) $country = 'Malawi';
    if( $code == 'MY' ) $country = 'Malaysia';
    if( $code == 'MV' ) $country = 'Maldives';
    if( $code == 'ML' ) $country = 'Mali';
    if( $code == 'MT' ) $country = 'Malta';
    if( $code == 'MH' ) $country = 'Marshall Islands';
    if( $code == 'MQ' ) $country = 'Martinique';
    if( $code == 'MR' ) $country = 'Mauritania';
    if( $code == 'MU' ) $country = 'Mauritius';
    if( $code == 'YT' ) $country = 'Mayotte';
    if( $code == 'MX' ) $country = 'Mexico';
    if( $code == 'FM' ) $country = 'Micronesia';
    if( $code == 'MD' ) $country = 'Moldova';
    if( $code == 'MC' ) $country = 'Monaco';
    if( $code == 'MN' ) $country = 'Mongolia';
    if( $code == 'ME' ) $country = 'Montenegro';
    if( $code == 'MS' ) $country = 'Montserrat';
    if( $code == 'MA' ) $country = 'Morocco';
    if( $code == 'MZ' ) $country = 'Mozambique';
    if( $code == 'MM' ) $country = 'Myanmar';
    if( $code == 'NA' ) $country = 'Namibia';
    if( $code == 'NR' ) $country = 'Nauru';
    if( $code == 'NP' ) $country = 'Nepal';
    if( $code == 'AN' ) $country = 'Netherlands Antilles';
    if( $code == 'NL' ) $country = 'Netherlands the';
    if( $code == 'NC' ) $country = 'New Caledonia';
    if( $code == 'NZ' ) $country = 'New Zealand';
    if( $code == 'NI' ) $country = 'Nicaragua';
    if( $code == 'NE' ) $country = 'Niger';
    if( $code == 'NG' ) $country = 'Nigeria';
    if( $code == 'NU' ) $country = 'Niue';
    if( $code == 'NF' ) $country = 'Norfolk Island';
    if( $code == 'MP' ) $country = 'Northern Mariana Islands';
    if( $code == 'NO' ) $country = 'Norway';
    if( $code == 'OM' ) $country = 'Oman';
    if( $code == 'PK' ) $country = 'Pakistan';
    if( $code == 'PW' ) $country = 'Palau';
    if( $code == 'PS' ) $country = 'Palestinian Territory';
    if( $code == 'PA' ) $country = 'Panama';
    if( $code == 'PG' ) $country = 'Papua New Guinea';
    if( $code == 'PY' ) $country = 'Paraguay';
    if( $code == 'PE' ) $country = 'Peru';
    if( $code == 'PH' ) $country = 'Philippines';
    if( $code == 'PN' ) $country = 'Pitcairn Islands';
    if( $code == 'PL' ) $country = 'Poland';
    if( $code == 'PT' ) $country = 'Portugal, Portuguese Republic';
    if( $code == 'PR' ) $country = 'Puerto Rico';
    if( $code == 'QA' ) $country = 'Qatar';
    if( $code == 'RE' ) $country = 'Reunion';
    if( $code == 'RO' ) $country = 'Romania';
    if( $code == 'RU' ) $country = 'Russian Federation';
    if( $code == 'RW' ) $country = 'Rwanda';
    if( $code == 'BL' ) $country = 'Saint Barthelemy';
    if( $code == 'SH' ) $country = 'Saint Helena';
    if( $code == 'KN' ) $country = 'Saint Kitts and Nevis';
    if( $code == 'LC' ) $country = 'Saint Lucia';
    if( $code == 'MF' ) $country = 'Saint Martin';
    if( $code == 'PM' ) $country = 'Saint Pierre and Miquelon';
    if( $code == 'VC' ) $country = 'Saint Vincent and the Grenadines';
    if( $code == 'WS' ) $country = 'Samoa';
    if( $code == 'SM' ) $country = 'San Marino';
    if( $code == 'ST' ) $country = 'Sao Tome and Principe';
    if( $code == 'SA' ) $country = 'Saudi Arabia';
    if( $code == 'SN' ) $country = 'Senegal';
    if( $code == 'RS' ) $country = 'Serbia';
    if( $code == 'SC' ) $country = 'Seychelles';
    if( $code == 'SL' ) $country = 'Sierra Leone';
    if( $code == 'SG' ) $country = 'Singapore';
    if( $code == 'SK' ) $country = 'Slovakia (Slovak Republic)';
    if( $code == 'SI' ) $country = 'Slovenia';
    if( $code == 'SB' ) $country = 'Solomon Islands';
    if( $code == 'SO' ) $country = 'Somalia, Somali Republic';
    if( $code == 'ZA' ) $country = 'South Africa';
    if( $code == 'GS' ) $country = 'South Georgia and the South Sandwich Islands';
    if( $code == 'ES' ) $country = 'Spain';
    if( $code == 'LK' ) $country = 'Sri Lanka';
    if( $code == 'SD' ) $country = 'Sudan';
    if( $code == 'SR' ) $country = 'Suriname';
    if( $code == 'SJ' ) $country = 'Svalbard & Jan Mayen Islands';
    if( $code == 'SZ' ) $country = 'Swaziland';
    if( $code == 'SE' ) $country = 'Sweden';
    if( $code == 'CH' ) $country = 'Switzerland, Swiss Confederation';
    if( $code == 'SY' ) $country = 'Syrian Arab Republic';
    if( $code == 'TW' ) $country = 'Taiwan';
    if( $code == 'TJ' ) $country = 'Tajikistan';
    if( $code == 'TZ' ) $country = 'Tanzania';
    if( $code == 'TH' ) $country = 'Thailand';
    if( $code == 'TL' ) $country = 'Timor-Leste';
    if( $code == 'TG' ) $country = 'Togo';
    if( $code == 'TK' ) $country = 'Tokelau';
    if( $code == 'TO' ) $country = 'Tonga';
    if( $code == 'TT' ) $country = 'Trinidad and Tobago';
    if( $code == 'TN' ) $country = 'Tunisia';
    if( $code == 'TR' ) $country = 'Turkey';
    if( $code == 'TM' ) $country = 'Turkmenistan';
    if( $code == 'TC' ) $country = 'Turks and Caicos Islands';
    if( $code == 'TV' ) $country = 'Tuvalu';
    if( $code == 'UG' ) $country = 'Uganda';
    if( $code == 'UA' ) $country = 'Ukraine';
    if( $code == 'AE' ) $country = 'United Arab Emirates';
    if( $code == 'GB' ) $country = 'United Kingdom';
    if( $code == 'US' ) $country = 'United States of America';
    if( $code == 'UM' ) $country = 'United States Minor Outlying Islands';
    if( $code == 'VI' ) $country = 'United States Virgin Islands';
    if( $code == 'UY' ) $country = 'Uruguay, Eastern Republic of';
    if( $code == 'UZ' ) $country = 'Uzbekistan';
    if( $code == 'VU' ) $country = 'Vanuatu';
    if( $code == 'VE' ) $country = 'Venezuela';
    if( $code == 'VN' ) $country = 'Vietnam';
    if( $code == 'WF' ) $country = 'Wallis and Futuna';
    if( $code == 'EH' ) $country = 'Western Sahara';
    if( $code == 'YE' ) $country = 'Yemen';
    if( $code == 'ZM' ) $country = 'Zambia';
    if( $code == 'ZW' ) $country = 'Zimbabwe';
    if( $country == '') $country = $code;
    return $country;
}

function update_scriptolution_top_rated($userid, $toprated)
{
	global $config, $conn;
	$scriptolution_toprated_count = intval($config['scriptolution_toprated_count']);
	$scriptolution_toprated_rating = intval($config['scriptolution_toprated_rating']);
	$query = "select good, bad from ratings where USERID='".mysqli_real_escape_string($conn->_connectionID, $userid)."'"; 
	$results=$conn->execute($query);
	$f = $results->getrows();
	$grat = 0;
	$brat = 0;
	for($i=0;$i<count($f);$i++)
	{
		$tgood = $f[$i]['good'];
		$tbad = $f[$i]['bad'];
		if($tgood == "1")
		{
			$grat++;	
		}
		elseif($tbad == "1")
		{
			$brat++;	
		}
	}
	$g = $grat;
	$b = $brat;
	$t = $g + $b;
	if($t > 0)
	{
		$r = (($g / $t) * 100);
		$r = round($r, 1);
				
		if($t >= $scriptolution_toprated_count && $r >= $scriptolution_toprated_rating)
		{
			$querym="UPDATE members SET toprated='1' WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $userid)."' limit 1";
			$conn->execute($querym);
		}
		elseif($toprated == "1")
		{
			$querym="UPDATE members SET toprated='0' WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $userid)."' limit 1";
			$conn->execute($querym);	
		}
	}
	
}

function get_ctp($IID)
{
	global $config,$conn;
	$query = "select ctp from order_items where IID='".mysqli_real_escape_string($conn->_connectionID, $IID)."'"; 
	$executequery=$conn->execute($query);
	$rctp = $executequery->fields['ctp'];
	return $rctp;
}

function insert_get_ctp($a)
{
	global $config,$conn;
	$query = "select ctp from order_items where IID='".mysqli_real_escape_string($conn->_connectionID, $a[IID])."'"; 
	$executequery=$conn->execute($query);
	$rctp = $executequery->fields['ctp'];
	return $rctp;
}

function insert_get_scriptolution_notifications($a)
{
    global $config,$conn;
	$UID = intval($a['USERID']);
	if($UID > 0)
	{
		$query = "select * from fiverrscript_dotcom_notity where USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."' order by NID desc limit 10"; 
		$results = $conn->execute($query);
		$returnthis = $results->getrows();
	}
	return $returnthis;
}

function insert_get_scriptolution_featcats($a)
{
    global $config,$conn;
	$query = "select * from categories where scriptolution_featuredcat='1' order by rand()"; 
	$results = $conn->execute($query);
	$returnthis = $results->getrows();
	return $returnthis;
}

function scriptolution_dotcom_fiverrscript_dotcom($scriptolution_doing, $UID, $OID)
{
    global $config,$conn;
	$UID = intval($UID);
	$OID = intval($OID);
	if($UID > 0 && $OID > 0 && $scriptolution_doing != "")
	{
		if($scriptolution_doing == "scriptolution_buyer_requirements")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='scriptolution_buyer_requirements', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		elseif($scriptolution_doing == "fiverrscript_dotcom_neworder")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='fiverrscript_dotcom_neworder', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		elseif($scriptolution_doing == "fiverrscript_dotcom_orderdelivered")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='fiverrscript_dotcom_orderdelivered', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		elseif($scriptolution_doing == "fiverrscript_dotcom_orderupdate")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='fiverrscript_dotcom_orderupdate', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		elseif($scriptolution_doing == "fiverrscript_dotcom_orderdeliveryreject")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='fiverrscript_dotcom_orderdeliveryreject', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		elseif($scriptolution_doing == "mutual_cancellation_request")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='mutual_cancellation_request', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		elseif($scriptolution_doing == "scriptolution_abort_cancellation")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='scriptolution_abort_cancellation', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		elseif($scriptolution_doing == "scriptolution_reject_cancellation")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='scriptolution_reject_cancellation', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		elseif($scriptolution_doing == "scriptolution_accept_cancellation")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='scriptolution_accept_cancellation', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		elseif($scriptolution_doing == "seller_cancellation")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='seller_cancellation', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		elseif($scriptolution_doing == "fiverrscript_dotcom_orderfeedback")
		{
			$query = "INSERT INTO fiverrscript_dotcom_notity SET USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."', scriptolution_type='fiverrscript_dotcom_orderfeedback', time_added='".time()."', scriptolution_OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', scriptolution_unread='1'"; 
			$executequery=$conn->execute($query);
		}
		
		
	}
}

function scriptolution_banned_words_chk($phrase)
{
	global $conn, $config;
	$query = "SELECT word from bans_words";
	$executequery = $conn->Execute($query);
	$bwords = $executequery->getarray();
	$found = 0;
	$words = explode(" ", $phrase);
	foreach($words as $word)
	{
		foreach($bwords as $bword)
		{
			if($word == $bword[0])
			{
				$found++;
			}
			else
			{
				$pos2 = strpos($word, $bword[0]);
				if($pos2 !== false)
				{
					$found++;
				}
			}
		}
	}
	if($found > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function get_percent($scriptolutionuserid)
{
	global $conn;
	$query = "select good, bad from ratings where USERID='".mysqli_real_escape_string($conn->_connectionID, $scriptolutionuserid)."'"; 
	$results=$conn->execute($query);
	$f = $results->getrows();
	$grat = 0;
	$brat = 0;
	for($i=0;$i<count($f);$i++)
	{
		$tgood = $f[$i]['good'];
		$tbad = $f[$i]['bad'];
		if($tgood == "1")
		{
			$grat++;	
		}
		elseif($tbad == "1")
		{
			$brat++;	
		}
	}
	$g = $grat;
	$b = $brat;
	$t = $g + $b;
	if($t > 0)
	{
		$r = (($g / $t) * 100);
		return round($r, 1);
	}
	else
	{
		return 0;
	}
}

function insert_scriptolution_rating_stars($a)
{
	global $conn;
    global $config;
	$scriptolutionuserid = $a['scriptolutionuserid'];
	$scriptolutionperc = get_percent($scriptolutionuserid);
	$rating = $scriptolutionperc/20;
    $count = floor($rating);
    $thecount = 0;
	for($i=0;$i<$count;$i++)
	{
		$returnthis.="<li><a href=\"#\"><img src=\"".$config['imageurl']."/scriptolution_user_rate_full.png\" /></a></li>";
		$thecount++;
	}
	$ratingcount = $rating;
	$ratingcount = $ratingcount - $count;
    if(($ratingcount >= 0.5) && ($ratingcount < 1))
    {
		$returnthis.="<li><a href=\"#\"><img src=\"".$config['imageurl']."/scriptolution_user_rate_half.png\" /></a></li>";
		$thecount++;
	}
    elseif (($ratingcount > 0) && ($ratingcount < 0.5))
	{
		$returnthis.="<li><a href=\"#\"><img src=\"".$config['imageurl']."/scriptolution_user_rate_blank.png\" /></a></li>";
		$thecount++;
	}
	for($i=$thecount;$i<5;$i++)
	{
		$returnthis.="<li><a href=\"#\"><img src=\"".$config['imageurl']."/scriptolution_user_rate_blank.png\" /></a></li>";
	}
    if($rating > 0)
	{
		echo $returnthis; 
	}
	else
	{
		echo "<li><a href=\"#\"><img src=\"".$config['imageurl']."/scriptolution_user_rate_blank.png\" /></a></li><li><a href=\"#\"><img src=\"".$config['imageurl']."/scriptolution_user_rate_blank.png\" /></a></li><li><a href=\"#\"><img src=\"".$config['imageurl']."/scriptolution_user_rate_blank.png\" /></a></li><li><a href=\"#\"><img src=\"".$config['imageurl']."/scriptolution_user_rate_blank.png\" /></a></li><li><a href=\"#\"><img src=\"".$config['imageurl']."/scriptolution_user_rate_blank.png\" /></a></li>";
	}
}

function get_percent_allusers($scriptolutionpid)
{
	global $conn;
	$query = "select good, bad from ratings where PID='".mysqli_real_escape_string($conn->_connectionID, $scriptolutionpid)."'"; 
	$results=$conn->execute($query);
	$f = $results->getrows();
	$grat = 0;
	$brat = 0;
	for($i=0;$i<count($f);$i++)
	{
		$tgood = $f[$i]['good'];
		$tbad = $f[$i]['bad'];
		if($tgood == "1")
		{
			$grat++;	
		}
		elseif($tbad == "1")
		{
			$brat++;	
		}
	}
	$g = $grat;
	$b = $brat;
	$t = $g + $b;
	if($t > 0)
	{
		$r = (($g / $t) * 100);
		return round($r, 1);
	}
	else
	{
		return 0;
	}
}

function insert_scriptolution_rating_stars_big($a)
{
	global $conn;
    global $config;
	$scriptolutionpid = $a['scriptolutionpid'];
	$scriptolutionperc = get_percent_allusers($scriptolutionpid);
	$rating = $scriptolutionperc/20;
    $count = floor($rating);
    $thecount = 0;
	for($i=0;$i<$count;$i++)
	{
		$returnthis.="<img src=\"".$config['imageurl']."/scriptolution_star_big_on.png\" />";
		$thecount++;
	}
	$ratingcount = $rating;
	$ratingcount = $ratingcount - $count;
    if(($ratingcount >= 0.5) && ($ratingcount < 1))
    {
		$returnthis.="<img src=\"".$config['imageurl']."/scriptolution_star_big_half.png\" />";
		$thecount++;
	}
    elseif (($ratingcount > 0) && ($ratingcount < 0.5))
	{
		$returnthis.="<img src=\"".$config['imageurl']."/scriptolution_star_big_off.png\" />";
		$thecount++;
	}
	for($i=$thecount;$i<5;$i++)
	{
		$returnthis.="<img src=\"".$config['imageurl']."/scriptolution_star_big_off.png\" />";
	}
    if($rating > 0)
	{
		echo $returnthis; 
	}
	else
	{
		echo "<img src=\"".$config['imageurl']."/scriptolution_star_big_off.png\" /><img src=\"".$config['imageurl']."/scriptolution_star_big_off.png\" /><img src=\"".$config['imageurl']."/scriptolution_star_big_off.png\" /><img src=\"".$config['imageurl']."/scriptolution_star_big_off.png\" /><img src=\"".$config['imageurl']."/scriptolution_star_big_off.png\" />";
	}
}

function issue_refund_admin($buyer,$OID,$rprice)
{
    global $conn;
	if($buyer > 0 && $OID > 0 && $rprice > 0)
	{
		$query = "select status, price, PID from orders where OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."' AND USERID='".mysqli_real_escape_string($conn->_connectionID, $buyer)."'"; 
		$executequery=$conn->execute($query);
		$status = $executequery->fields['status'];
		$price = $executequery->fields['price'];
		$rprice = $price;
		$THEPID = $executequery->fields['PID'];
		if($price > 0)
		{
			if($status != "3" && $status != "7")
			{
				$query = "INSERT INTO payments SET USERID='".mysqli_real_escape_string($conn->_connectionID, $buyer)."', OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."', time='".time()."', price='".mysqli_real_escape_string($conn->_connectionID, $rprice)."', t='0'"; 
				$executequery=$conn->execute($query);
				$transid = mysqli_insert_id($conn->_connectionID);
				if($transid > 0)
				{
					$query = "UPDATE payments SET cancel='1' WHERE OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."' AND t='1' AND cancel='0' limit 1"; 
					$executequery=$conn->execute($query);
					
					$query = "UPDATE members SET funds=funds+$rprice WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $buyer)."' limit 1"; 
					$executequery=$conn->execute($query);
				}
			}
			
			if($status == "5")
			{
				if($THEPID > 0)
				{
					$query = "select wd FROM payments where OID='".mysqli_real_escape_string($conn->_connectionID, $OID)."'"; 
					$executequery=$conn->execute($query);
					$wdx = $executequery->fields['wd'];
					if($wdx == "1")
					{
						$query = "select USERID, ctp FROM posts where PID='".mysqli_real_escape_string($conn->_connectionID, $THEPID)."'"; 
						$executequery=$conn->execute($query);
						$ctp = $executequery->fields['ctp'];
						$selleruserid = $executequery->fields['USERID'];
						
						$scriptolutioncalamount = $price - $ctp;
						
						$sql="UPDATE members SET afunds=afunds-$scriptolutioncalamount WHERE USERID='".mysqli_real_escape_string($conn->_connectionID, $selleruserid)."'";
						$conn->Execute($sql);
					}
				}
			}
			
		}
	}
}

function insert_scriptolutionnotificationcounter($a)
{
    global $config,$conn;
	$UID = intval(cleanit($_SESSION['USERID']));
	if($UID > 0)
	{
		$query = "select count(*) as total from fiverrscript_dotcom_notity where USERID='".mysqli_real_escape_string($conn->_connectionID, $UID)."' AND scriptolution_unread='1'"; 
		$executequery=$conn->execute($query);
		$returnthis = $executequery->fields['total']+0;
	}
	else
	{ 
		$returnthis = 0;
	}
	return $returnthis;
}

function insert_scriptolutionoffercnt($a)
{
    global $conn;
	$REQUESTID = intval($a['REQUESTID']);
	$query = "select count(*) as total from offerscriptolution where REQUESTID='".mysqli_real_escape_string($conn->_connectionID, $REQUESTID)."'"; 
	$executequery=$conn->execute($query);
	$total = $executequery->fields['total']+0;
	return $total;

}
function insert_scriptolutionseo($a)
{
        global $conn;
        $query="SELECT seo FROM categories WHERE CATID='".mysqli_real_escape_string($conn->_connectionID, $a['CATID'])."' limit 1";
        $executequery=$conn->execute($query);
        $seo = $executequery->fields['seo'];
		return $seo;
}
function scriptolutiondelete_request($RID)
{
	global $config, $conn;
	$RID = intval($RID);
	if($RID > 0)
	{		
		$query = "select REQUESTID from scriptolutionrequests WHERE REQUESTID='".mysqli_real_escape_string($conn->_connectionID, $RID)."' AND USERID='".mysqli_real_escape_string($conn->_connectionID, $_SESSION['USERID'])."'"; 
		$results = $conn->execute($query);
		$REQUESTID=$results->fields['REQUESTID'];
		if($REQUESTID > 0)
		{
			$query = "DELETE FROM offerscriptolution WHERE REQUESTID='".mysqli_real_escape_string($conn->_connectionID, $REQUESTID)."'";
			$conn->Execute($query);	
			$query = "DELETE FROM scriptolutionrequests WHERE REQUESTID='".mysqli_real_escape_string($conn->_connectionID, $REQUESTID)."'";
			$conn->Execute($query);		
		}
	}
}
function scriptolutiondelete_requestadmin6($RID)
{
	global $config, $conn;
	$RID = intval($RID);
	if($RID > 0)
	{		
		$query = "select REQUESTID from scriptolutionrequests WHERE REQUESTID='".mysqli_real_escape_string($conn->_connectionID, $RID)."'"; 
		$results = $conn->execute($query);
		$REQUESTID=$results->fields['REQUESTID'];
		if($REQUESTID > 0)
		{
			$query = "DELETE FROM offerscriptolution WHERE REQUESTID='".mysqli_real_escape_string($conn->_connectionID, $REQUESTID)."'";
			$conn->Execute($query);	
			$query = "DELETE FROM scriptolutionrequests WHERE REQUESTID='".mysqli_real_escape_string($conn->_connectionID, $REQUESTID)."'";
			$conn->Execute($query);		
		}
	}
}
function insert_afewscriptolutionrequests($a)
{
    global $config,$conn;
	$query = "SELECT A.*, C.username, C.country, C.toprated from scriptolutionrequests A, members C where A.active='1' AND C.status='1' AND A.USERID=C.USERID order by REQUESTID desc limit 5"; 
	$results = $conn->execute($query);
	$returnthis = $results->getrows();
	return $returnthis;
}
?>