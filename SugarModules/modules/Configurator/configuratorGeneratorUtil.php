<?php
/**
 * Utility class I use to generate the code needed for configuration pages.
 * User: blake
 * Date: 9/3/12
 * Time: 8:56 PM
 *
 * Instructions:
 * 1) edit the asterisk_config_meta.php
 * 2) Run script. (php configuratorGeneratorUtil.php > asterisk_configurator_table.tpl)
 * 3) Look at the asterisk_configurator_table.tpl if any mod_strings aren't defined they'll be listed there.
 */
define('sugarEntry', true);
define('configurator_util', true);
@require_once 'language/en_us.lang.php';
@require_once 'asterisk_config_meta.php';
global $config_meta;
global $mod_strings;

print "<!-- THIS FILE IS AUTOGENERATED BY RUNNING configuratorGeneratorUtil > asterisk_configurator_table.tpl -->";
sleep(3);

$columnSmarty=<<<'END'
    <td nowrap width="10%" class="dataLabel">{$MOD.LBL_@@UPPER@@}
      {if !empty($MOD.LBL_@@UPPER@@_DESC)}
          [<a href="#" title="{$MOD.LBL_@@UPPER@@_DESC}">?</a>]:
      {/if}
    </td>
    <td width="25%" class="dataField">
    {if empty($config.@@NORMAL@@ )}
        {assign var='@@NORMAL@@' value=$asterisk_config.@@NORMAL@@}
    {else}
        {assign var='@@NORMAL@@' value=$config.@@NORMAL@@}
    {/if}
        <input type='@@TYPE@@' name='@@NORMAL@@' size="45" value='{$@@NORMAL@@}'>
    </td>
END;

$i = 0;
$prevSection='';
    foreach ($config_meta as $key => $value) {
    $in = $columnSmarty ;

    // Once section changes we insert a new header
    if( $value['section'] != $prevSection ) {
        $prevSection = $value['section'];
        if( $i%2 == 1 ) {
            print "<TD>&nbsp;</TD><TD>&nbsp;</TD> </tr>";
            $i++;
        }
        print "\n\n<TR><td colspan=\"4\">&nbsp;&nbsp;<TR><TD colspan=\"4\"><h3>{$value['section']}</h3></TD></tr>\n";
        $sectionHdr = "LBL_ASTERISK_SECTIONHDR_" . strtoupper($value['section']);
        if( array_key_exists($sectionHdr, $mod_strings) ) {
            print "<TR><TD colspan=\"4\">{$mod_strings[$sectionHdr]}</TD></tr>";
        }
    }

    if( ($i % 2) == 0)
        print "\n\n<TR>\n\n";

    $type = "textbox";
    if( isset($value['type'] ) ) {
        if( $value['type'] == "bool") {
            $type = "checkbox"; // checkbox is too hard to support since it's value isn't submitted when form is posted and it's unchecked!
        }
        else {
            $type = $value['type'];
        }
    }

    $out  = str_replace(
        array('@@UPPER@@','@@NORMAL@@','@@TYPE@@'),
        array(strtoupper($key),$key,$type),
        $in);

    print $out . "\n\n";

    $i++;
}

if( $i%2 == 1 ) {
    print "<TD>&nbsp;</TD><TD>&nbsp;</TD> </tr>";
}

//print "\n\n\n -- FOR SugarModules/Configurator/language/en_us.lang.php ------\n\n";

$missingCnt = 0;
foreach ($config_meta as $key => $value) {
    $msKey = "LBL_" . strtoupper($key);
    if( !array_key_exists($msKey, $mod_strings) ) {
        print "$" . "mod_strings['" . "LBL_" . strtoupper($key) . "'] = 'TODO DEFINE';" . "\n";
        $missingCnt++;
    }
}
if( $missingCnt == 0 ) {
    //print "Congrats, all required modstrings for field labels are defined!\n";
}
else {
    print "WARNING: don't forget to define the modstrings above!\n\n";
}








