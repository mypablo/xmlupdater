<?php

/* 
Plugin Name: xml-updater
Plugin URL:
Description: Daily xml update at wordpress dashboard
Author:
Author URL:
Version: 0.1

*/



add_action("admin_menu", "addMenu");
function addMenu()
{
    add_menu_page("XML Updater","XML Updater",'administrator', "XML Updater","XMLmenu");
}

function XMLmenu()

{    
    //Count products of xml
    function xmlcount(){

        $xml = file_get_contents(dirname(__DIR__, 2) . "/uploads/feed/skroutz.xml");
        $xmlData = new SimpleXMLElement($xml);
        return (count($xmlData->xpath("//products//product//uid")));

    }



    // Create table & columns in db
    function xmltableCreate(){

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE `{$wpdb->base_prefix}xmln` (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        text text NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    //insert values in columns
    function xmltableInsert(){

        global $wpdb;
        $tablename = $wpdb->prefix . "xmln";

        $value = xmlcount();
        $now     = new DateTime(); 
        $datesent = $now->format('Y-m-d H:i:s'); 
        $sql = $wpdb->prepare("INSERT INTO `$tablename` (`text`, `time`) values (%s, %s)", $value, $datesent);

        $wpdb->query($sql);

    }

    //printing last row for customer
    function printLastRow(){

        global $wpdb;
        $results = $wpdb->get_results( "SELECT time,text FROM wp_xmln ORDER BY id DESC LIMIT 1" );


        foreach ($results as $res){
            echo $res->time. " " .$res->text . "<br>";
        }
    }


    // echo '<input type="submit" name="submit" value="check xml"/>';
    
   
    
    
    

    





   
}