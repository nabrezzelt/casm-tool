<?php

class Helper
{
    /**
     * Format e. g. a Froum-Topic in a string that can uses in a URL for SEO.
     *
     * Undocumented function long description
     *
     * @param string $source e. g. a Forum-Topic
     **/

    public static function formatURL($source)
    {
        $result = preg_replace('[ ]', '-', $source);
        $result = preg_replace('[ß]', 'ss', $result);
        $result = preg_replace('/[^A-Za-z0-9\-]/', '', $result);

        return strtolower($result);
    }

    public static function formatBytes($bytes, $precision = 2) 
    { 
        //$units = array('B', 'KB', 'MB', 'GB', 'TB'); 

        //$bytes = max($bytes, 0); 
        //$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        //$pow = min($pow, count($units) - 1); 

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow)); 

        //return round($bytes, $precision) . ' ' . $units[$pow];
        return $bytes . " Bytes"; 
    } 

    public static function getExtension($filename) 
    {
        return end(explode('.', $filename));;
    }

    /**
     * Find the ProjectID in the Database from the given AttachmentID
     *
     * @param int $attachmentID ID of a Attachment-File
     **/
    public static function getProjectIDByAttachmentID($attachmentID)
    {
        $query = "SELECT category.projectID FROM attachments
                  JOIN bugs
                  ON attachments.bugID = bugs.id
                  JOIN category
                  ON bugs.categoryID = category.id
                  WHERE attachments.id = '$attachmentID'";
        $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        $row = mysql_fetch_assoc($res);

        return $row['projectID'];
    }

    public static function getProjectIDByBugID($bugID)
    {
        $query = "SELECT category.projectID FROM bugs
                  JOIN category
                  ON bugs.categoryID = category.id
                  WHERE bugs.id = '$bugID'";
        $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        $row = mysql_fetch_assoc($res);

        return $row['projectID'];
    }

    public static function noPermission($permissionID, $dismissible = false) 
    {
        $permission = Permission::getPermissionByID($permissionID);

        $error = "<div class='alert " . (($dismissible) ? "alert-dismissible" : "") . " alert-danger'>
                    " . (($dismissible) ? "<button type='button' class='close' data-dismiss='alert'>&times;</button>" : "") . "
                    <strong>You have no permission to do that!</strong><br>
                    <strong>ErrorCode: </strong> " . $permission->getErrorCode() . "<br>
                    <strong>ErrorMessage: </strong> " . $permission->getErrorMessage() . "<br>
                </div>";
        return $error;
    }

    function humanTiming($time)
    {
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) 
        {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }

    public static function redirectTo($url, $time = 0)
    {
        echo "<meta http-equiv='refresh' content='$time; URL=$url'>";
    }

    /*
    public static function shortString($source, $limit = 100)
    {
        return (substr($source, 0, $limit) + "...");
    }
    */

    public static function getBugQuickInfo($bugID)
    {
        //$bug = Bug::getBugByID($bugID);
    }

    public static function SQLErrorFormat($msg, $query, $method, $file, $line)
    {
        $re  = "<table class='table'>
                    <tr class='bg-danger'><th colspan='2'><h4>SQL-Query Error</h4></th></tr>
                    <tr><th>Error-Message</th><td>$msg</td></tr>
                    <tr><th>Query</th><td>$query</td></tr>
                    <tr><th>Class-Method</th><td>$method</td></tr>
                    <tr><th>File-Line</th><td>$file <i>in Line</i> $line</td></tr>
                </table>";
        return $re;
    }

    public static function AlertNoProjectAccess()
    {
        return "<div class='alert alert-dismissible alert-danger'>
                                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                <strong>No Project-Access!</strong><br>
                                <strong>ErrorCode: </strong> noP-ac <br>
                                <strong>ErrorMessage: </strong> You have no Permission to view this Project<br>
                            </div>";
    }

    public static function showAlert($content, $color, $dismissible = false) 
    {
        echo   "<div class='alert " . (($dismissible) ? "alert-dismissible" : "") . " alert-$color'>
                    " . (($dismissible) ? "<button type='button' class='close' data-dismiss='alert'>&times;</button>" : "") . "
                    $content
                </div>";
    }
}