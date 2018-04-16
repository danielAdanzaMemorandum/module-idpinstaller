<?php
/*
 *  IdPRef - IdP de Referencia para SIR 2 basado en SimpleSAMLPHP v1.13.1
 * =========================================================================== *
 *
 * Copyright (C) 2014 - 2015 by the Spanish Research and Academic Network.
 * This code was developed by Auditoria y Consultoría de Privacidad y Seguridad
 * (PRiSE http://www.prise.es) for the RedIRIS SIR service (SIR: 
 * http://www.rediris.es/sir)
 *
 * *****************************************************************************
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 *
 * ************************************************************************** */

/** 
 * Plantilla para el paso 6 del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */
?>
<script type="text/javascript">
    function showCertForm() {
        var elem = document.getElementById('cert_type');
        if (elem.value == "create") {
            SimpleSAML_show('create_form');
            SimpleSAML_hide('import_form');
        } else {            
            SimpleSAML_hide('create_form');
            SimpleSAML_show('import_form');
        }
    }
</script>
<?php
//PASO 6 del instalador.
$step      = 6;
$next_step = 7;
$options   = array(
        'create' => "Crear certificado",
        'import'  => "Importar certificado"
);
?>
<p><?php echo $this->t('{idpinstaller:idpinstaller:step6_cert_select}'); ?>
    <select id="cert_type" name="cert_type" onchange="showCertForm();">
        <?php
        $selected = 'create';
        foreach ($options as $k => $v) {
            echo "<option value='$k'";
            if($selected==$k){
                echo' selected=true';
            }
            echo ">$v</option>";
        }
        ?>
    </select>
</p>
<?php

$inputs_create = '<h4>'.$this->t('{idpinstaller:idpinstaller:step6_create_title}').'</h4>';
$inputs_create.= "<p style='margin-top:3px;'>" . $this->t('{idpinstaller:idpinstaller:step6_cert_info}');
$inputs_create.= '<input type="text" name="organization_name" value="" style="width: 500px; margin-top:5px;"/></p>';

$inputs_import = '<h4>'.$this->t('{idpinstaller:idpinstaller:step6_import_title}').'</h4>';
$inputs_import.= "<p style='margin-top:3px;'>". $this->t('{idpinstaller:idpinstaller:step6_cert_info2}').'</p>';
$inputs_import.= 'Certificado:<br/>';
$inputs_import.= '<textarea name="private_key" style="width: 90%; height: 8em; font-family: Monospace"></textarea><br/>';
$inputs_import.= 'Clave privada:<br/>';
$inputs_import.= '<textarea name="certificate" style="width: 90%; height: 8em; font-family: Monospace"></textarea><br/><br/>';

?>
<div id = "create_form" <?php if ($selected == "import") { ?>style="display:none;" <?php } ?>>
    <?php
    drawButton($next_step, $this->t('{idpinstaller:idpinstaller:next_step}'), '', $inputs_create);
    ?>
</div>
<div id = "import_form" <?php if ($selected == "create") { ?>style="display:none;" <?php } ?>>
    <?php
    drawButton($next_step, $this->t('{idpinstaller:idpinstaller:next_step}'), '', $inputs_import);
    ?>
</div>
<?php
$dir_certs = realpath(__DIR__ . "/../../../") . "/cert";
echo "<p style='margin-top:3px;'>" . $this->t('{idpinstaller:idpinstaller:step6_footer_note}') . '<i>' . $dir_certs . '</i></p>';
?>
