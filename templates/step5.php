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
 * Plantilla para el paso 5 del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */
?>

<script type="text/javascript">
    function showDatasource() {
        var elem = document.getElementById('data_source_type');
        if (elem.value == "ldap") {
            SimpleSAML_show('ldap_form');
            SimpleSAML_hide('pdo_form');
        } else {
            SimpleSAML_hide('ldap_form');
            SimpleSAML_show('pdo_form');
        }
    }
</script>
<form action="" method="post" style="margin-top:20px;">    
    <?php
//PASO 5 del instalador.
    $step      = 5;
    $next_step = 6;
    $options   = array(
        'ldap' => "LDAP",
        'pdo'  => "PDO"
    );
    
    if (strcmp($this->data['sir']['datasources'], "all") == 0) {
        $ldap = true;
        $pdo  = true;
    } else if (strcmp($this->data['sir']['datasources'], "ldap") == 0) {
        $ldap = true;
        $pdo = false;
        unset($options['pdo']);
    } else if (strcmp($this->data['sir']['datasources'], "pdo") == 0) {
        $pdo  = true;
        $ldap = false;
        unset($options['ldap']);
    }
    if (count($this->data['sir']['errors']) > 0) {
        $button_msg = $this->t('{idpinstaller:idpinstaller:try_again_button}');
        $next_step  = 5;
    } else {
        $button_msg = $this->t('{idpinstaller:idpinstaller:next_step}');
    }
    ?>
    <p><?php echo $this->t('{idpinstaller:idpinstaller:step5_datasource_select}'); ?>
        <select id="data_source_type" name="data_source_type" onchange="showDatasource();">
            <?php
            $selected = isset($this->data['sir']['datasource_selected'])?$this->data['sir']['datasource_selected']:'ldap';
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
    <div onload = "showDatasource();">
        <div id = "ldap_form" <?php if ((!$ldap && $pdo)||$selected=="pdo") { ?>style="display:none" <?php } ?>>
            <h3><?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_title}'); ?></h3>
            <p>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_hostname}'); ?><br/>
                <input type="text" name="ldap_hostname" value="" style="width: 300px;"/><br/>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_example}'); ?><br/>
            </p>
            <p>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_port}'); ?><br/>
                <input type="text" name="ldap_port" value="" style="width: 300px;"/><br/>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_port_example}'); ?><br/>
            </p>
            <p>   
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_anonymous_bind}'); ?><br/>
                <select name="ldap_anonymous_bind">
                    <option value="1"><?php echo $this->t('{idpinstaller:idpinstaller:step5_anonbind_yes}'); ?></option>
                    <option value="0"><?php echo $this->t('{idpinstaller:idpinstaller:step5_anonbind_no}'); ?></option>
                </select><br/>
            </p>            
            <p>En el caso de realizar bind anónimo especifique el DN y password:</p>
            <p>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_binddn}'); ?><br/>
                <input type="text" name="ldap_binddn" value="" style="width: 300px;"/><br/>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_binddn_example}'); ?><br/>
            </p>    
            <p>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_bindpassword}'); ?><br/>
                <input type="text" name="ldap_bindpassword" value="" style="width: 300px;"/><br/>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_bindpassword_example}'); ?><br/>
            </p>              
            <p>   
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_enable}'); ?><br/>
                <select name="ldap_enable_tls">
                    <option value="0"><?php echo $this->t('{idpinstaller:idpinstaller:step5_yes}'); ?></option>
                    <option value="1"><?php echo $this->t('{idpinstaller:idpinstaller:step5_no}'); ?></option>
                </select><br/>
            </p>
            <p>    
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_referral}'); ?><br/>
                <select name="ldap_referral">
                    <option value="0"><?php echo $this->t('{idpinstaller:idpinstaller:step5_yes}'); ?></option>
                    <option value="1"><?php echo $this->t('{idpinstaller:idpinstaller:step5_no}'); ?></option>
                </select><br/>
            </p>   
            <div class="caution" style="min-height:45px;">
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_info}'); ?>
            </div>
        </div>
        <div id="pdo_form" <?php if ((!$pdo && $ldap) || ($ldap && $pdo && $selected=="ldap")) { ?>style="display:none" <?php } ?>>
            <h3><?php echo $this->t('{idpinstaller:idpinstaller:step5_pdo_title}'); ?></h3>
            <p>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_pdo_dsn}'); ?><br/>
                <input type="text" name="pdo_dsn" value="" style="width: 600px;"/><br/>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_pdo_example}'); ?><br/>
            </p>
            <p>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_pdo_username}'); ?><br/>
                <input type="text" name="pdo_username" value="" style="width: 300px;"/><br/>
            </p>
            <p>
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_pdo_password}'); ?><br/>
                <input type="password" name="pdo_password" value="" style="width: 300px;"/><br/>
            </p>
            <div class="caution" style="min-height:45px;">
                <?php echo $this->t('{idpinstaller:idpinstaller:step5_ldap_info}'); ?>
            </div>
        </div>
        <input type="hidden" name="step" value="<?php echo $next_step; ?>"/>
        <input type="submit" value="<?php echo $button_msg; ?>"/>
    </div>
</form>
