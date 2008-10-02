<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net
[BEGIN_SED]
File=forums.php
Version=125
Updated=2008-feb-27
Type=Core
Author=Neocrome
Description=Forums
[END_SED]
==================== */

/**
 * @package Seditio-N
 * @version 0.0.1
 * @copyright Partial copyright (c) 2008 Cotonti Team
 * @license BSD License
 */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$id = sed_import('id','G','INT');
$s = sed_import('s','G','INT');
$q = sed_import('q','G','INT');
$p = sed_import('p','G','INT');
$d = sed_import('d','G','INT');
$o = sed_import('o','G','ALP');
$w = sed_import('w','G','ALP',4);
$quote = sed_import('quote','G','INT');
$poll = sed_import('poll','G','INT');
$vote = sed_import('vote','G','INT');

/* === Hook === */
$extp = sed_getextplugins('forums.editpost.first');
if (is_array($extp))
{ foreach($extp as $k => $pl) { include_once($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

sed_blockguests();
sed_check_xg();

$sql = sed_sql_query("SELECT * FROM $db_forum_posts WHERE fp_id='$p' and fp_topicid='$q' and fp_sectionid='$s' LIMIT 1");

if ($row = sed_sql_fetcharray($sql))
{
	$fp_text = $row['fp_text'];
	$fp_posterid = $row['fp_posterid'];
	$fp_postername = $row['fp_postername'];
	$fp_sectionid = $row['fp_sectionid'];
	$fp_topicid = $row['fp_topicid'];
	$fp_updated = $row['fp_updated'];
	$fp_updater = $row['fp_updater'];

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('forums', $s);

	if (!$usr['isadmin'] && $fp_posterid!=$usr['id'])
	{
		sed_log("Attempt to edit a post without rights", 'sec');
		sed_die();
	}
	sed_block($usr['auth_read']);
}
else
{ sed_die(); }

$sql = sed_sql_query("SELECT fs_state, fs_title, fs_category, fs_allowbbcodes, fs_allowsmilies, fs_masterid FROM $db_forum_sections WHERE fs_id='$s' LIMIT 1");

if ($row = sed_sql_fetcharray($sql))
{
	if ($row['fs_state'])
	{
		header("Location: " . SED_ABSOLUTE_URL . sed_url('message', "msg=602", '', true));
		exit;
	}

	$fs_title = $row['fs_title'];
	$fs_category = $row['fs_category'];
	$fs_allowbbcodes = $row['fs_allowbbcodes'];
	$fs_allowsmilies = $row['fs_allowsmilies'];
	$fs_masterid = $row['fs_masterid'];
}
else
{ sed_die(); }

$sql = sed_sql_query("SELECT ft_state, ft_mode, ft_title, ft_desc, ft_poll FROM $db_forum_topics WHERE ft_id='$q' LIMIT 1");

if ($row = sed_sql_fetcharray($sql))
{
	if ($row['ft_state'] && !$usr['isadmin'])
	{
		header("Location: " . SED_ABSOLUTE_URL . sed_url('message', "msg=603", '', true));
		exit;
	}
	$ft_title = sed_cc($row['ft_title']);
	$ft_desc = sed_cc($row['ft_desc']);
	$ft_fulltitle = ($row['ft_mode']==1) ? "# ".$ft_title : $ft_title;
	$sys['sublocation'] = 'q'.$q;
}
else
{ sed_die(); }

if ($a=='update')
{
	/* === Hook === */
	$extp = sed_getextplugins('forums.editpost.update.first');
	if (is_array($extp))
	{ foreach($extp as $k => $pl) { include_once($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */

	$rtext = sed_import('rtext','P','HTM');
	$rtopictitle = sed_import('rtopictitle','P','TXT', 64);
	$rtopicdesc = sed_import('rtopicdesc','P','TXT', 64);
	$rupdater = ($fp_posterid == $usr['id'] && ($sys['now_offset'] < $fp_updated + 300) && empty($fp_updater) ) ? '' : $usr['name'];

	if(!empty($rtext))
	{
		if($cfg['parser_cache'])
		{
			$rhtml = sed_sql_prep(sed_parse(sed_cc($rtext), $cfg['parsebbcodeforums'] && $fs_allowbbcodes, $cfg['parsesmiliesforums'] && $fs_allowsmilies, 1));
		}
		else
		{
			$rhtml = '';
		}
		$rtext = sed_sql_prep($rtext);
		$sql = sed_sql_query("UPDATE $db_forum_posts SET fp_text='$rtext', fp_html = '$rhtml', fp_updated='".$sys['now_offset']."', fp_updater='".sed_sql_prep($rupdater)."' WHERE fp_id='$p'");
	}

	if (!empty($rtopictitle))
	{
		$sql = sed_sql_query("SELECT fp_id FROM $db_forum_posts WHERE fp_topicid='$q' ORDER BY fp_id ASC LIMIT 1");
		if ($row = sed_sql_fetcharray($sql))
		{
			$fp_idp = $row['fp_id'];
			if ($fp_idp==$p)
			{
				if (mb_substr($rtopictitle, 0 ,1)=="#")
				{ $rtopictitle = str_replace('#', '', $rtopictitle); }
				$sql = sed_sql_query("UPDATE $db_forum_topics SET ft_title='".sed_sql_prep($rtopictitle)."', ft_desc='".sed_sql_prep($rtopicdesc)."' WHERE ft_id='$q'");
			}
		}
	}

	/* === Hook === */
	$extp = sed_getextplugins('forums.editpost.update.done');
	if (is_array($extp))
	{ foreach($extp as $k => $pl) { include_once($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */

	sed_forum_sectionsetlast($fp_sectionid);
	header("Location: " . SED_ABSOLUTE_URL . sed_url('forums', "m=posts&p=".$p, '#'.$p, true));
	exit;
}

$sql = sed_sql_query("SELECT fp_id FROM $db_forum_posts WHERE fp_topicid='$q' ORDER BY fp_id ASC LIMIT 1");

if ($row = sed_sql_fetcharray($sql))
{
	$fp_idp = $row['fp_id'];
	if ($fp_idp==$p)
	{
		$edittopictitle = ($fp_idp==$p) ? $L['Topic'].": <input type=\"text\" class=\"text\" name=\"rtopictitle\" value=\"".sed_cc($ft_title)."\" size=\"56\" maxlength=\"64\" /><br />".$L['Description'].": <input type=\"text\" class=\"text\" name=\"rtopicdesc\" value=\"".sed_cc($ft_desc)."\" size=\"56\" maxlength=\"64\" /><br />" : '';
	}
}

$bbcodes = ($cfg['parsebbcodeforums'] && $fs_allowbbcodes) ? sed_build_bbcodes('editpost', 'rtext', $L['BBcodes']) : '';
$bbcodes_local = ($cfg['parsebbcodeforums'] && $fs_allowbbcodes) ? sed_build_bbcodes_local(99) : '';
$smilies = ($cfg['parsesmiliesforums'] && $fs_allowsmilies) ? sed_build_smilies('editpost', 'rtext', $L['Smilies']) : '';
$smilies_local = ($cfg['parsesmiliesforums'] && $fs_allowsmilies) ? sed_build_smilies_local(20) : '';
$pfs = sed_build_pfs($usr['id'], 'editpost', 'rtext', $L['Mypfs']);
$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; ".sed_build_pfs(0, "editpost", "rtext", $L['SFS']) : '';
$morejavascript .= sed_build_addtxt('editpost', 'rtext');
$post_main = '<textarea class="editor" name="rtext" rows="20" cols="56">'.sed_cc($fp_text).'</textarea>';

$toptitle = "<a href=\"".sed_url('forums')."\">".$L['Forums']."</a> ".$cfg['separator']." ".sed_build_forums($s, $fs_title, $fs_category, true, $fs_masterid)." <a href=\"".sed_url('forums', "m=topics&s=".$s)."\">  ".$cfg['separator']." <a href=\"".sed_url('forums', "m=posts&p=".$p, "#".$p)."\">".$ft_fulltitle."</a> ";
$toptitle .= $cfg['separator']." <a href=\"".sed_url('forums', "m=editpost&s=$s&q=".$q."&p=".$p."&".sed_xg())."\">".$L['Edit']."</a>";
$toptitle .= ($usr['isadmin']) ? " *" : '';

$sys['sublocation'] = $fs_title;
$out['subtitle'] = $L['Forums'];

/* === Hook === */
$extp = sed_getextplugins('forums.editpost.main');
if (is_array($extp))
{ foreach($extp as $k => $pl) { include_once($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

require_once $cfg['system_dir'] . '/header.php';

$mskin = sed_skinfile(array('forums', 'editpost', $fs_category, $fp_sectionid));
$t = new XTemplate($mskin);

if (!empty($error_string))
{
	$t->assign("FORUMS_POSTS_EDITPOST_ERROR_BODY",$error_string);
	$t->parse("MAIN.FORUMS_EDITPOST_ERROR");
}

$t->assign(array(
	"FORUMS_EDITPOST_PAGETITLE" => $toptitle,
	"FORUMS_EDITPOST_SUBTITLE" => "#".$fp_posterid." ".$fp_postername." - ".date($cfg['dateformat'], $fp_updated + $usr['timezone'] * 3600)." ".$usr['timetext'],
	"FORUMS_EDITPOST_SEND" => sed_url('forums', "m=editpost&a=update&s=".$s."&q=".$q."&p=".$p."&".sed_xg()),
	"FORUMS_EDITPOST_TEXT" => $edittopictitle.$post_main."<br />".$bbcodes." ".$smilies." ".$pfs,
	"FORUMS_EDITPOST_TEXTONLY" => $edittopictitle.$post_main,
	"FORUMS_EDITPOST_TEXTBOXER" => $edittopictitle.$post_main."<br />".$smilies." ".$pfs,
	"FORUMS_EDITPOST_SMILIES" => $smilies,
	"FORUMS_EDITPOST_BBCODES" => $bbcodes,
	"FORUMS_EDITPOST_MYPFS" => $pfs,
	"FORUMS_EDITPOST_SMILIESLOCAL" => $smilies_local,
	"FORUMS_EDITPOST_BBCODESLOCAL" => $bbcodes_local
));

/* === Hook === */
$extp = sed_getextplugins('forums.editpost.tags');
if (is_array($extp))
{ foreach($extp as $k => $pl) { include_once($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require_once $cfg['system_dir'] . '/footer.php';

?>
