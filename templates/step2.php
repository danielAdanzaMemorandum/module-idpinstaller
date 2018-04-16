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
 * Plantilla para el paso 2 del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */
$step      = 2;
$next_step = 3;
if (count($this->data['sir']['errors']) > 0) {
    $button_msg = $this->t('{idpinstaller:idpinstaller:try_again_button}');
} else {
    $button_msg = $this->t('{idpinstaller:idpinstaller:next_step}');
}
?>
<form action="" method="post">    
    <h4><?php echo $this->t('{idpinstaller:idpinstaller:step2_access_title}'); ?></h4>
    <input type="hidden" name="step" value="<?php echo $next_step; ?>">
    <?php echo $this->t('{idpinstaller:idpinstaller:step2_access_password}'); ?><br/>
    <input type="password" value="" name="ssphp_password" style="width:200px;"><br/>
    <?php echo $this->t('{idpinstaller:idpinstaller:step2_access_password2}'); ?><br/>
    <input type="password" value="" name="ssphp_password2" style="width:200px;"><br/>

    <h4><?php echo $this->t('{idpinstaller:idpinstaller:step2_contact_title}'); ?></h4>
    <?php echo $this->t('{idpinstaller:idpinstaller:step2_contact_name}'); ?>:<br/>
    <input type="text" value="" name="ssphp_technicalcontact_name" style="width:300px;"><br/>
    <?php echo $this->t('{idpinstaller:idpinstaller:step2_contact_email}'); ?>:<br/>
    <input type="text" value="" name="ssphp_technicalcontact_email" style="width:300px;"><br/>
    <p><?php echo $this->t('{idpinstaller:idpinstaller:step2_contact_info}'); ?></p>
    
    <h4><?php echo $this->t('{idpinstaller:idpinstaller:step2_organization_title}'); ?></h4>
    <?php echo $this->t('{idpinstaller:idpinstaller:step2_organization_name}'); ?>:<br/>
    <input type="text" value="" name="ssphp_organization_name" style="width:300px;"><br/>
    <?php echo $this->t('{idpinstaller:idpinstaller:step2_organization_description}'); ?>:<br/>
    <input type="text" value="" name="ssphp_organization_description" style="width:300px;"><br/>
    <?php echo $this->t('{idpinstaller:idpinstaller:step2_organization_info_url}'); ?>:<br/>
    <input type="text" value="" name="ssphp_organization_info_url" style="width:300px;"><br/>

    <br/><input type="submit" value="<?php echo $button_msg; ?>"></input>
</form>
