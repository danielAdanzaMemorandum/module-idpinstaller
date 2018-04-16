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
 * Plantilla para el paso 1 del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */

$step = 1;
if (count($this->data['sir']['errors']) > 0) {
    $button_msg = $this->t('{idpinstaller:idpinstaller:try_again_button}');
    $next_step  = 1;
} else {
    $button_msg = $this->t('{idpinstaller:idpinstaller:next_step}');
    $next_step  = 2;
    echo "<h3>" . $this->t('{idpinstaller:idpinstaller:step1_title}') . "</h3>";
    if (count($this->data['sir']['info']) > 0) {
        echo "<p>" . implode("<br/>", $this->data['sir']['info']) . "</p>";
    }
}
?>
<?php drawButton($next_step, $button_msg, 'style="margin-top:20px;"'); ?>
