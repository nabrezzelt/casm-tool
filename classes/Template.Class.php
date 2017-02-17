<?php
    /**
     * 
     */
    class Template 
    {
        public function saveTemplate($id, $name, $creatorID, $editorID, $auszeit, $beschleunigung, $fachteams, $serviceID, $managerGroupID, $managerSubGroupID, $managerName, $changeEnd, $changeStart, $dringlichkeit,  $durchlaufzeit, $genehmigerGroupID, $genehmigerSubGroupID, $auszeitDauer, $gutfallServiceImpact, $schlechtfallServiceImpact, $durchfuehrung, $koordinator, $koordinierungsbedarf, $kundenauswirkungGut, $kundenauswirkungSchlecht, $kurzbeschreibung, $langbeschreibung, $CI, $redundanz, $risikoklasse, $risikomassnahme, $rueckfallprozedur, $steuerung, $ausloeserID, $taetigkeit, $taetigkeitUmfang, $vierAugen, $auflagen, $assignmentGroupID, $assignmentSubGroupID, $assignmentName)
        {
           $query = "UPDATE templates
                     SET 
                     name = '$name',
                     creatorID = '$creatorID',
                     editorID = '" . unserialize($_SESSION['user'])->getID() . "',
                     auszeit = '$auszeit',
                     beschleunigung = '$beschleunigung',
                     fachteams = '$fachteams',
                     serviceID = '$serviceID',
                     managerGroupID = '$'managerGroupID,
                     managerSubGroupID = '$managerSubGroupID',
                     managerName = '$managerName',
                     changeEnd = '$changeEnd',
                     changeStart = '$changeStart',
                     dringlichkeit = '$dringlichkeit',
                     durchlaufzeit = '$durchlaufzeit',
                     genehmigerGroupID = '$genehmigerGroupID',
                     genehmigerSubGroupID = '$genehmigerSubGroupID',
                     auszeitDauer = '$auszeitDauer',
                     gutfallServiceImpact = '$gutfallServiceImpact',
                     schlechtfallServiceImpact = '$schlechtfallServiceImpact',
                     durchfuehrung = '$durchfuehrung',
                     koordinator = '$koordinator',
                     koordinierungsbedarf = '$koordinierungsbedarf',
                     kundenauswirkungGut = '$kundenauswirkungGut',
                     kundenauswirkungSchlecht = '$kundenauswirkungSchlecht',
                     kurzbeschreibung = '$kurzbeschreibung',
                     langbeschreibung = '$langbeschreibung',
                     CI = '$CI',
                     redundanz = '$redundanz',
                     risikoklasse = '$risikoklasse',
                     risikomassnahme = '$risikomassnahme',
                     rueckfallprozedur = '$rueckfallprozedur',
                     steuerung = '$steuerung',
                     ausloeserID = '$ausloeserID',
                     taetigkeit = '$taetigkeit',
                     taetigkeitUmfang = '$taetigkeitUmfang',
                     vierAugen = '$vierAugen',
                     auflagen = '$auflagen',
                     assignmentGroupID = '$assignmentGroupID',
                     assignmentSubGroupID = '$assignmentSubGroupID',
                     assignmentName = '$assignmentName'                     
                     WHERE id = $id";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }        
    }
    

?>