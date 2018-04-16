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
 * Plantilla para el paso N del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */

$this->data['header'] = $this->t('{idpinstaller:idpinstaller:idpinstaller_header}');
$this->includeAtTemplateBase('includes/header.php');
if (count($this->data['sir']['errors']) > 0 && (int)$this->data['sir']['step']!==1 && (int)$this->data['sir']['step']!==4 && (int)$this->data['sir']['step']!==8 && (int)$this->data['sir']['step']!==9) {    
        $this->data['sir']['step'] =  (int)$this->data['sir']['step'] -1;
}
$this->data['sir']['errors'] = array_merge($this->data['sir']['errors'], $this->data['sir']['errors2']);
?>
<h2><?php
    if(count($this->data['sir']['errors']) > 0){
        echo $this->t("{idpinstaller:idpinstaller:step_error_h3}");
        echo $this->t("{idpinstaller:idpinstaller:step".$this->data['sir']['step']."_h3}");
    } else {
        echo $this->t("{idpinstaller:idpinstaller:step".$this->data['sir']['step']."_h3}");
    }
    ?></h2>
<div class="idpinstaller_step" style="height: 500px;display:block;">
    <?php
    if (count($this->data['sir']['errors']) > 0) {
        $img_error = substr($_SERVER['PHP_SELF'], 0, -24 )."resources/icons/experience/gtk-dialog-error.48x48.png";
        echo '<div style="border-left: 1px solid #e8e8e8; border-bottom: 1px solid #e8e8e8; background: #f5f5f5;">';
	echo '	<img style="margin-right: 10px;margin-left: 5px;" class="float-l erroricon" src='.$img_error.'>';
        echo '  <p style="padding-top: 5px " >'.implode("<br/>", $this->data['sir']['errors']) . '</p>';
        echo '</div>';        
    }
    if(count($this->data['sir']['warning'])>0){
        echo '<div class="caution" style="min-height:45px;">';
        echo '<p>'.implode("<br/>", $this->data['sir']['warning']).'</p>';
        echo '</div>';
    }
    include(__DIR__ . '/step' . $this->data['sir']['step'] . '.php');
    ?>
</div>
<?php $this->includeAtTemplateBase('includes/footer.php'); ?>
