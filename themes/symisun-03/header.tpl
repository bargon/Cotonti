<!-- BEGIN: HEADER -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- vital meta tags -->
<meta http-equiv="Content-Type" content="{HEADER_META_CONTENTTYPE}; charset={HEADER_META_CHARSET}" />
<title>{HEADER_TITLE}</title>
<meta name="description" content="{HEADER_META_DESCRIPTION}" />
<meta name="keywords" content="{HEADER_META_KEYWORDS}" />
<!-- 2ndary -->
<meta name="generator" content="Cotonti http://www.cotonti.com" />
<link rel="canonical" href="{HEADER_CANONICAL_URL}" />
<!-- files linkage -->
{HEADER_BASEHREF}
<link rel="shortcut icon" href="favicon.ico" />
<link rel="apple-touch-icon" href="apple-touch-icon.png" />
<link href="themes/{PHP.theme}/{PHP.theme}.css" type="text/css" rel="stylesheet" />
{HEADER_HEAD}
{HEADER_COMPOPUP}
<!-- SymiSun 03 / Designed By: SymiSun* - SymiSun.Com -->
</head>

<body>
<div id="parent"><a id="topofpage" name="topofpage"></a>
	<div id="header">
    	<a href="{PHP.cfg.mainurl}" title="{PHP.L.Home}">
        <img src="themes/{PHP.theme}/img/logo.jpg" width="209" height="89" alt="Cotonti" id="logo" class="colleft" />
        </a>
        <span id="tagline">Agile Website Engine</span>
        <div id="info">
        	<div class="padding10">
            	<strong>Cotonti</strong> is more than just an <strong>open source</strong> <em>Content Management System</em> (<strong>CMS</strong>). It is a <strong>powerful</strong> <em>platform</em>, <strong>flexible</strong> and <strong>easy to customize</strong>, that enables you to <em>power websites</em> with a focus on <strong>security</strong>, <strong>speed</strong> and <strong>design freedom</strong>.
            </div>
        </div>
        <div id="user" class="colleft">
        	<div class="padding20">
            	<h3>{PHP.themelang.header.user}</h3>
                
				<!-- BEGIN: GUEST -->
                <form action="login.php&amp;a=check" method="post">                
				<p>                
				<input type="text" name="rusername" maxlength="32" value="{PHP.L.Username}" onfocus="if (this.value == '{PHP.L.Username}') this.value = ''" class="userinput" />
				<input type="password" name="rpassword" maxlength="32" class="userinput" />
				<input type="hidden" name="rremember" value="1" />
				<button type="submit" class="login">{PHP.L.Login}</button>
				</p>
				</form>
                <p class="margin5">{PHP.L.No} {PHP.Ls.Members.1} {PHP.L.Unknown} <a href="users.php?m=register"><strong>{PHP.L.Register}</strong></a></p>
                <!-- END: GUEST -->
                
				<!-- BEGIN: USER -->
                <span style="font-size:.9em">                
				<b>{HEADER_LOGSTATUS}</b><br />
				
				<img src="
				<!-- IF {PHP.usr.profile.user_avatar} -->
				{PHP.usr.profile.user_avatar}
				<!-- ELSE -->
				themes/{PHP.theme}/img/blank.png
				<!-- ENDIF -->
				" alt="{PHP.L.Avatar" class="usrav" />
				
                <a href="users.php?m=details&amp;id={PHP.usr.id}&amp;u={PHP.usr.name}">{PHP.L.Profile}</a> &nbsp;
                <!-- IF {PHP.usr.messages} > 0 -->
				<a href="pm.php" class="usrpm"><strong>{PHP.usr.messages} {PHP.L.New} {PHP.L.Private_Messages}</strong></a>
                <!-- ELSE -->
                <span style="font-size:1.2em">&nbsp;</span>
                <!-- ENDIF -->
                <br />{HEADER_NOTICES}<br />{HEADER_USER_LOGINOUT} &nbsp; {HEADER_USER_ADMINPANEL}
                </span>
	            <!-- END: USER -->
				
            </div>
        </div>
        <div id="latest" class="colright">
        	<div id="forums" class="tabcontent">
                <h4 class="none">{PHP.themelang.index.Newinforums}</h4>
                {RECENT_FORUMS}
            </div>
			<!-- If you want an extra tab between Forums and Pages. Change also header-latest_original.png in header-latest.png
			<div id="comments" class="tabcontent">
				<h4 class="none">{PHP.themelang.index.Recentcomments}</h4>
					{PLUGIN_LATESTCOMMENTS}
            </div>
			-->
            <div id="pages" class="tabcontent">
            	<h4 class="none">{PHP.themelang.index.Recentadditions}</h4>
                {RECENT_PAGES}
            </div>
            <div class="pos">
                <ul id="recent" class="tabs">
                    <li class="f"><a href="#" rel="forums" class="selected"><span>{PHP.themelang.index.Newinforums}</span></a></li>
					<!-- If you want an extra tab between Forums and Pages
					<li class="c"><a href="#" rel="comments"><span>{PHP.themelang.index.Recentcomments}</span></a></li>
					-->
                    <li class="p"><a href="#" rel="pages"><span>{PHP.themelang.index.Recentadditions}</span></a></li>
                </ul>
            </div>
            <script type="text/javascript">
				var latest=new ddtabcontent("recent")
				latest.setpersist(true)
				latest.setselectedClassTarget("link") //"link" or "linkparent"
				latest.init()
			</script>
			
		
            <span id="online">{PHP.L.Online}: <a href="plug.php?e=whosonline">{PHP.out.whosonline}</a></span>
        </div>
        <div id="sitemenu">
            <h4 class="none">{PHP.L.Navigation}</h4>
            <ul>
            	<li><a href="index.php"><strong>Home</strong></a></li>
				<li><a href="forums.php"><strong>Forums</strong></a></li>
				<li><a href="list.php?c=news"><strong>News</strong></a></li>
				<li><a href="list.php?c=articles"><strong>Articles</strong></a></li>
				<li class="last"><a href="plug.php?e=search"><strong>Search</strong></a></li>
                <li class="edgel">&nbsp;</li>
                <li class="edger">&nbsp;</li>
            </ul>
        </div>
        <div id="hsearch">
        	<h4 class="none">{PHP.L.Search}</h4>
			
			<form id="search" action="plug.php?e=search" method="post">
			<p>
				<input type="text" name="rsq" value="{PHP.L.Search}..." onblur="if(this.value=='') this.value='{PHP.L.Search}...';" onfocus="if(this.value=='{PHP.L.Search}...') this.value='';" class="sq" maxlength="40"/>
				<input value="" type="submit" class="sb" />
			</p>
		</form>
			
			
        </div>
        <a href="rss.php" title="{PHP.L.RSS_Feeds}"><img src="themes/{PHP.theme}/img/rss.jpg" width="50" height="56" alt="{PHP.L.RSS_Feeds}" id="rss" /></a>
    </div>
    
    <div id="edgel"></div>
    <div id="edger"></div>

<!-- END: HEADER -->