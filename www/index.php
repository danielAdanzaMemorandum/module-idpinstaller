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
 * Punto de entrada de ejecución del módulo.
 * 
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */
include_once(__DIR__ . '/../lib/functions.php');
$info = array();
$errors = array();
$errors2 = array();
$warning = array();
$step = 1;


if(isset($_REQUEST['step'])){
    $step = $_REQUEST['step'];
   
}
$config = SimpleSAML_Configuration::getInstance();
$t = new SimpleSAML_XHTML_Template($config, 'idpinstaller:stepn.php');

$sirinfo = array(
	'info' => &$info, 
	'errors' => &$errors,
        'errors2' => &$errors2,
	'warning' => &$warning,
        'step' => &$step,
        'ssphpobj' => $t
);
SimpleSAML_Module::callHooks("step$step", $sirinfo);

$t->data['sir'] = $sirinfo;
$t->show();
?>
