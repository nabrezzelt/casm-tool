<?php
    require_once("includes/connect.inc.php");
    require_once("includes/Autoloader.Class.php");
    Autoloader::Init(Dev::DEBUG);

    function handler()
    {
        $re = "";
        if(isset($_GET['act'])) 
        {
            $act = $_GET['act'];
        }
        else
        {
            $act = "default";
        }
        
        switch ($act) {
            case 'show':
                if(isset($_GET['id']))
                {
                    if(Permission::hasPermission(Permission::TOOL_VIEW_TEMPLATES, unserialize($_SESSION['user'])->getID()))
                    {
                        $re .= formatTemplate(mysql_real_escape_string($_GET['id']));                        
                    }
                    else
                    {
                        return Helper::noPermission(Permission::TOOL_VIEW_TEMPLATES);
                    }
                }
                break;

            default:
                if(Permission::hasPermission(Permission::TOOL_VIEW_TEMPLATES, unserialize($_SESSION['user'])->getID()))
                {
                    $re .= "<div class='row'>
                                <div class='col-sm-8'><h3>Templates</h3></div>
                                <div class='col-sm-4 text-right'><a class='btn btn-default' href='#'>Create new Template</a></div>
                            </div>";

                    $query = "SELECT id, name, creatorID FROM templates";
                    $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

                    $re .= "<table class='table table-bordered'>";
                    $re .= "<tr><th>Name</th><th>Ersteller</th></tr>";

                    while ($row = mysql_fetch_assoc($res)) 
                    {
                        $re .= "<tr><td><a href='templates.php?act=show&id=" . $row['id'] . "'>" . $row['name'] . "</a></td><td>" . User::getUser((int) $row['creatorID'])->getUsername() . "</td></tr>";
                    }

                    $re .= "</table>";
                }
                else
                {
                    return Helper::noPermission(Permission::TOOL_VIEW_TEMPLATES);
                }
            break;
        }

        return $re;
    }

    function createTemplate()
    {
         $re  = "<form class='form-horizontal'>
                    <h3>Template-Bearbeiten</h3>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Template-Name:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='name'>
                        </div>
                    </div>                   
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Auszeit</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='auszeit'>
                                <option value='Ja'>Ja</option>
                                <option value='Nein'>Nein</option>
                                <option value='Leistung beeinträchtigt'>Leistung beeinträchtigt</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Beschleunigung:</label>
                        <div class='col-sm-10'>
                        <textarea class='form-control' rows='3' name='beschleunigung'></textarea>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Fachteams:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='fachteams'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Service:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='serviceID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Manager-Group:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='managerGroupID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Manager-Sub-Group:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='managerSubGroupID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Manager-Name:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='managerName'>
                        </div>
                    </div>                                      
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Change-Ende:</label>
                        <div class='col-sm-10'>
                        <input type='number' min='0' max='24' class='form-control' name='changeEnd'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Change-Start:</label>
                        <div class='col-sm-10'>
                        <input type='number' min='0' max='24' class='form-control' name='changeStart'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Dringlichkeit:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='dringlichkeit'>
                                <option value='Gering'>Gering</option>
                                <option value='Mittel'>Mittel</option>
                                <option value='Hoch'>Hoch</option>
                                <option value='Sehr Hoch'>Sehr Hoch</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Durchlaufzeit:</label>
                        <div class='col-sm-10'>
                        <input type='number' class='form-control' name='durchlaufzeit'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Genehmigungs-Gruppe:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='genehmigerGroupID'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Genehmiger-Sub-Gruppe:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='genehmigerSubGroupID'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Dauer-Ausfallzeit:</label>
                        <div class='col-sm-10'>
                        <input type='time' class='form-control' name='auszeitDauer'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Gutfall-Service-Impact:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='gutfallServiceImpact'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Gutfall-Service-Impact:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='schlechtfallServiceImpact'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Durchführung:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='durchfuehrung'>
                                <option value='noch nie'>noch nie</option>
                                <option value='selten'>selten</option>
                                <option value='schon mehrmals'>schon mehrmals</option>
                                <option value='oft'>oft</option>
                            </select>
                        </div>
                    </div> 
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Koordinator:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='koordinator'>
                                <option value='Ja'>Ja</option>
                                <option value='Nein'>Nein</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Koordinierungsbedarf:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='koordinierungsbedarf'>
                                <option value='Gering'>Gering</option>
                                <option value='Mittel'>Mittel</option>
                                <option value='Hoch'>Hoch</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Kundenauswirkung-Gutfall:</label>
                       <div class='col-sm-10'>
                            <select class='form-control' name='kundenauswirkungGut'>
                                <option value='Keine'>Keine</option>                            
                                <option value='Gering'>Gering</option>
                                <option value='Mittel'>Mittel</option>
                                <option value='Hoch'>Hoch</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Kundenauswirkung-Schlechtfall:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='kundenauswirkungSchlecht'>
                                <option value='Keine'>Keine</option>                            
                                <option value='Gering'>Gering</option>
                                <option value='Mittel'>Mittel</option>
                                <option value='Hoch'>Hoch</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Kurzbeschreibung:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='kurzbeschreibung'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Langbeschreibung:</label>
                        <div class='col-sm-10'>
                        <textarea class='form-control' rows='3' name='langbeschreibung'></textarea>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>CI:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='CI'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Redundanz:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='redundanz'>
                                <option value='Ja'>Ja</option>
                                <option value='Nein'>Nein</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Risikoklasse:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='risikoklasse'>
                                <option value='Standard'>Ja</option>
                                <option value='Minor'>Minor</option>
                                <option value='Major'>Major</option>
                                <option value='Significant'>Significant</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Risikomaßnahme:</label>
                        <div class='col-sm-10'>
                        <textarea class='form-control' rows='3' name='risikomassnahme'></textarea>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Rückfallprozedur:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='rueckfallprozedur'>
                                <option value='Ja'>Ja</option>
                                <option value='Nein'>Nein</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Steuerung:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='steuerung'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Auslöser:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='ausloeserID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Tätigkeit:</label>
                        <div class='col-sm-10'>
                        <textarea class='form-control' rows='3' name='taetigkeit'></textarea>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Tätigkeit-Umfang:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='taetigkeitUmfang'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Vier-Augen:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='vierAugen'>
                                <option value='Ja'>Ja</option>
                                <option value='Nein'>Nein</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Auflagen:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='auflagen'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Assignment-Group:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='assignmentGroupID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Assignment-Sub-Group:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='assignmentSubGroupID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Assignment-Name:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='assignmentName'>
                        </div>
                    </div>                    

                     <div class='form-group'>
                        <div class='col-sm-offset-2 col-sm-10'>
                        <button type='submit' class='btn btn-default'>Submit</button>
                        </div>
                    </div>                   
                </form>";

                return $re;
    }

    function formatTemplate($templateID)
    {
        $query = "SELECT * FROM templates WHERE id = $templateID";
        $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        $row = mysql_fetch_assoc($res);


        $re  = "<h3>Template-Bearbeiten</h3>"; 
        $re .= "<form class='form-horizontal'>                    
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Template-Name:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='name' value='" . $row['name'] . "'>
                        </div>
                    </div>  
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Auszeit</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='auszeit'>
                                <option value='Ja' " . (($row['auszeit'] == 'Ja') ? 'selected' : '') . ">Ja</option>
                                <option value='Nein' " . (($row['auszeit'] == 'Nein') ? 'selected' : '') . ">Nein</option>
                                <option value='Leistung beeintraechtigt' " . (($row['auszeit'] == 'Leistung beeintraechtigt') ? 'selected' : '') . ">Leistung beeinträchtigt</option>
                            </select>                            
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Beschleunigung:</label>
                        <div class='col-sm-10'>
                        <textarea class='form-control' rows='3' name='beschleunigung'>" . $row['beschleunigung'] . "</textarea>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Fachteams:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='fachteams' value='" . $row['fachteams'] . "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Service:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='serviceID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Manager-Group:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='managerGroupID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Manager-Sub-Group:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='managerSubGroupID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Manager-Name:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='managerName' value='" . $row['managerName']. "'>
                        </div>
                    </div>                                      
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Change-Ende:</label>
                        <div class='col-sm-10'>
                        <input type='number' min='0' max='24' class='form-control' name='changeEnd' value='" . $row['changeEnd']. "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Change-Start:</label>
                        <div class='col-sm-10'>
                        <input type='number' min='0' max='24' class='form-control' name='changeStart' value='" . $row['changeStart']. "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Dringlichkeit:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='dringlichkeit'>
                                <option value='Gering' " . (($row['dringlichkeit'] == 'Gering') ? 'selected' : '') . ">Gering</option>
                                <option value='Mittel' " . (($row['dringlichkeit'] == 'Mittel') ? 'selected' : '') . ">Mittel</option>
                                <option value='Hoch' " . (($row['dringlichkeit'] == 'Hoch') ? 'selected' : '') . ">Hoch</option>
                                <option value='Sehr Hoch' " . (($row['dringlichkeit'] == 'Sehr Hoch') ? 'selected' : '') . ">Sehr Hoch</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Durchlaufzeit:</label>
                        <div class='col-sm-10'>
                        <input type='number' class='form-control' name='durchlaufzeit' value='" . $row['durchlaufzeit'] . "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Genehmigungs-Gruppe:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='genehmigerGroupID'>
                                //TODO!
                            </select>
                        </div>                        
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Genehmiger-Sub-Gruppe:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='genehmigerSubGroupID'>
                                //TODO!
                            </select>
                        </div>                        
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Dauer-Ausfallzeit:</label>
                        <div class='col-sm-10'>
                        <input type='time' class='form-control' name='auszeitDauer' value='" . $row['auszeitDauer'] . "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Gutfall-Service-Impact:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='gutfallServiceImpact' value='" . $row['gutfallServiceImpact'] . "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Gutfall-Service-Impact:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='schlechtfallServiceImpact' value='" . $row['schlechtfallServiceImpact'] . "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Durchführung:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='durchfuehrung'>
                                <option value='noch nie' " . (($row['durchfuehrung'] == 'noch nie') ? 'selected' : '') . ">noch nie</option>
                                <option value='selten' " . (($row['durchfuehrung'] == 'selten') ? 'selected' : '') . ">selten</option>
                                <option value='schon mehrmals' " . (($row['durchfuehrung'] == 'schon mehrmals') ? 'selected' : '') . ">schon mehrmals</option>
                                <option value='oft' " . (($row['durchfuehrung'] == 'oft') ? 'selected' : '') . ">oft</option>
                            </select>
                        </div>
                    </div> 
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Koordinator:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='koordinator'>
                                <option value='Ja' " . (($row['koordinator'] == 'Ja') ? 'selected' : '') . ">Ja</option>
                                <option value='Nein' " . (($row['koordinator'] == 'Nein') ? 'selected' : '') . ">Nein</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Koordinierungsbedarf:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='koordinierungsbedarf'>
                                <option value='Gering' " . (($row['koordinierungsbedarf'] == 'Gering') ? 'selected' : '') . ">Gering</option>
                                <option value='Mittel' " . (($row['koordinierungsbedarf'] == 'Mittel') ? 'selected' : '') . ">Mittel</option>
                                <option value='Hoch' " . (($row['koordinierungsbedarf'] == 'Hoch') ? 'selected' : '') . ">Hoch</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Kundenauswirkung-Gutfall:</label>
                       <div class='col-sm-10'>
                            <select class='form-control' name='kundenauswirkungGut'>
                                <option value='Keine' " . (($row['kundenauswirkungGut'] == 'Keine') ? 'selected' : '') . ">Keine</option>                            
                                <option value='Gering' " . (($row['kundenauswirkungGut'] == 'Gering') ? 'selected' : '') . ">Gering</option>
                                <option value='Mittel' " . (($row['kundenauswirkungGut'] == 'Mittel') ? 'selected' : '') . ">Mittel</option>
                                <option value='Hoch' " . (($row['kundenauswirkungGut'] == 'Hoch') ? 'selected' : '') . ">Hoch</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Kundenauswirkung-Schlechtfall:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='kundenauswirkungSchlecht'>
                                <option value='Keine' " . (($row['kundenauswirkungSchlecht'] == 'Keine') ? 'selected' : '') . ">Keine</option>                            
                                <option value='Gering' " . (($row['kundenauswirkungSchlecht'] == 'Gering') ? 'selected' : '') . ">Gering</option>
                                <option value='Mittel' " . (($row['kundenauswirkungSchlecht'] == 'Mittel') ? 'selected' : '') . ">Mittel</option>
                                <option value='Hoch' " . (($row['kundenauswirkungSchlecht'] == 'Hoch') ? 'selected' : '') . ">Hoch</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Kurzbeschreibung:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='kurzbeschreibung' value='" . $row['kurzbeschreibung'] . "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Langbeschreibung:</label>
                        <div class='col-sm-10'>
                        <textarea class='form-control' rows='3' name='langbeschreibung'>" . $row['langbeschreibung'] . "</textarea>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>CI:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='CI' value='" . $row['CI'] . "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Redundanz:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='redundanz'>
                                <option value='Ja' " . (($row['redundanz'] == 'Ja') ? 'selected' : '') . ">Ja</option>
                                <option value='Nein' " . (($row['redundanz'] == 'Nein') ? 'selected' : '') . ">Nein</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Risikoklasse:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='risikoklasse'>
                                <option value='Standard' " . (($row['risikoklasse'] == 'Standard') ? 'selected' : '') . ">Ja</option>
                                <option value='Minor' " . (($row['risikoklasse'] == 'Minor') ? 'selected' : '') . ">Minor</option>
                                <option value='Major' " . (($row['risikoklasse'] == 'Major') ? 'selected' : '') . ">Major</option>
                                <option value='Significant' " . (($row['risikoklasse'] == 'Significant') ? 'selected' : '') . ">Significant</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Risikomaßnahme:</label>
                        <div class='col-sm-10'>
                        <textarea class='form-control' rows='3' name='risikomassnahme'>" . $row['risikomassnahme'] . "</textarea>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Rückfallprozedur:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='rueckfallprozedur'>
                                <option value='Ja' " . (($row['rueckfallprozedur'] == 'Ja') ? 'selected' : '') . ">Ja</option>
                                <option value='Nein' " . (($row['rueckfallprozedur'] == 'Nein') ? 'selected' : '') . ">Nein</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Steuerung:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='steuerung' value='" . $row['steuerung'] . "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Auslöser:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='ausloeserID'>";

                            $ausloeser = Ausloeser::getAllAusloeser();
                            $ausloeser->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                            for ($ausloeser->rewind(); $ausloeser->valid(); $ausloeser->next())
                            {
                                $a = $ausloeser->current();
                                $re .= "<option value='" . $a->getID() . "' " . (($row['ausloeserID'] == $a->getID())?  : '') . ">" . $a->getName() . "</option>";
                            }

                    $re .= "</select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Tätigkeit:</label>
                        <div class='col-sm-10'>
                        <textarea class='form-control' rows='3' name='taetigkeit'>" . $row['taetigkeit'] . "</textarea>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Tätigkeit-Umfang:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='taetigkeitUmfang' value='" . $row['taetigkeitUmfang'] . "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Vier-Augen:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='vierAugen'>
                                <option value='Ja' " . (($row['vierAugen'] == 'Ja') ? 'selected' : '') . ">Ja</option>
                                <option value='Nein' " . (($row['vierAugen'] == 'Nein') ? 'selected' : '') . ">Nein</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Auflagen:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='auflagen' value='" . $row['auflagen'] . "'>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Assignment-Group:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='assignmentGroupID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Assignment-Sub-Group:</label>
                        <div class='col-sm-10'>
                            <select class='form-control' name='assignmentSubGroupID'>
                                //TODO!
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-2'>Assignment-Name:</label>
                        <div class='col-sm-10'>
                        <input type='text' class='form-control' name='assignmentName' value='" . $row['assignmentName'] . "'>
                        </div>
                    </div>                    

                     <div class='form-group'>
                        <div class='col-sm-offset-2 col-sm-10'>
                        <button type='submit' class='btn btn-default'>Submit</button>
                        </div>
                    </div>                   
                </form>";

                return $re;
    }
?>
