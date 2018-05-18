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
 * Paso 6 del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */

/**
 * Hook a ejecutar antes del paso 6 de la instalación
 * Comprueba los datos de conexión de la fuente de datos principal
 *
 * @param array &$data  Los datos a utilizar por las plantillas de tipo stepn
 */

////////////////////////////////////////////////////////////////
//  Nueva forma de hacerlo
////////////////////////////////////////////////////////////////
//Para dudas sobre el código: daniel.adanza@externos.rediris.com
//Hecho en Mayo / 2018
//Función para sobrescribir el authsources tomando como modelo el registrado en config-templates/authources.php

function overwriteAuthsources ()
{
        echo "Has entrado en la función authsources";

        $file = __DIR__ . '/../../../config-templates/authsources.php';
        $fopen = fopen($file, 'r');
        $fread = fread($fopen,filesize($file));
        fclose($fopen);

        //a continuación dividiremos el fichero en líneas
        $remove = "\n";
        $split = explode($remove, $fread);

        //declaramos también otras variables útiles para el recorrido del contenido del fichero
        $fileContent = "";
        $isCommentLong = false;
        $isArrayLong = 0;
        //creamos la variable config aux para no altearar el array original
        $configAux = $config;

    //mostramos por pantalla el array ConfigAux para verificar que accedemos a él correctamente
    echo implode(" ",$configAux);
}

////////////////////////////////////////////////////////////////
//  Fin del nuevo código
////////////////////////////////////////////////////////////////

function idpinstaller_hook_step6(&$data) {
    $data['datasources'] = getDataSources();
    if (isset($_REQUEST['data_source_type'])) {
        $ds_type = $_REQUEST['data_source_type'];
        if (strcmp($ds_type, "ldap") == 0 && ($data['datasources'] == "all" || $data['datasources'] == "ldap")) {
            if (array_key_exists('ldap_hostname', $_REQUEST) && !empty($_REQUEST['ldap_hostname']) && 
                    array_key_exists('ldap_port', $_REQUEST) && !empty($_REQUEST['ldap_port']) &&
                    array_key_exists('ldap_enable_tls', $_REQUEST) && array_key_exists('ldap_referral', $_REQUEST)) {
                $res = ldap_connect($_REQUEST['ldap_hostname'], $_REQUEST['ldap_port']);
                ldap_set_option($res, LDAP_OPT_PROTOCOL_VERSION,3);     
                if( !empty($_REQUEST['ldap_anonymous_bind']) && $_REQUEST['ldap_anonymous_bind'] != '0'){
                    $res = @ldap_bind($res); //anonymous bind
                }else{
                    $res = @ldap_bind($res,$_REQUEST['ldap_binddn'],$_REQUEST['ldap_bindpassword']); //non-anonymous bind
                }
                if (!$res) {
                    $data['errors'][]            = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step5_datasource_error}');
                    $data['datasource_selected'] = 'ldap';
                } else {
                    $filename                  = __DIR__ . '/../../../config/authsources.php';
                    include($filename);
                    $config['ldap_datasource'] = array(
                        'ldap:LDAP',
                        'hostname'          => $_REQUEST['ldap_hostname'].":".$_REQUEST['ldap_port'],
                        'enable_tls'        => $_REQUEST['ldap_enable_tls'] == 0 ? TRUE : FALSE,
                        'referrals'         => $_REQUEST['ldap_referral'] == 0 ? TRUE : FALSE,
                        'timeout'           => 30,
                        'debug'             => FALSE,
                        'attributes'        => NULL,
                        'dnpattern'         => "'uid=%username%,".$_REQUEST['ldap_binddn']."'" ,       // binddn if needed
                        'ldap.password'     => $_REQUEST['ldap_bindpassword'],  // ldap password if needed
                        'search.enable'     => FALSE,
                        'search.base'       => '',
                        'search.attributes' => array(),
                        'search.username'   => NULL,
                        'search.password'   => NULL,
                        'priv.read'         => FALSE,
                        'priv.username'     => NULL,
                        'priv.password'     => NULL,
                        'authority'         => "urn:mace:".$_SERVER['HTTP_HOST'],
                    );
                    if (array_key_exists('sql_datasource', $config)) {
                        unset($config['sql_datasource']);
                    }
                    $res2 = @file_put_contents($filename, '<?php  $config = ' . var_export($config, 1) . "; ?>");
                    
            overwriteAuthsources ();

            if (!$res2) {
                        $data['errors'][]            = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step2_contact_save_error}');
                        $data['errors'][]            = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step2_contact_save_error2}') . " <i>" . realpath($filename) . "</i>";
                        $data['datasource_selected'] = 'ldap';
                    }
                }
                return true;
            }
        } else if (strcmp($ds_type, "pdo") == 0 && ($data['datasources'] == "all" || $data['datasources'] == "pdo")) {
            if (array_key_exists('pdo_dsn', $_REQUEST) && !empty($_REQUEST['pdo_dsn'])) {
                $dsn      = $_REQUEST['pdo_dsn'];
                $username = isset($_REQUEST['pdo_username']) ? $_REQUEST['pdo_username'] : "";
                $password = isset($_REQUEST['pdo_password']) ? $_REQUEST['pdo_password'] : "";
                try {
                    $res = new PDO($dsn, $username, $password);
                } catch (PDOException $e) {
                    $res = false;
                }
                if ($res === false) {
                    $data['errors'][]            = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step5_datasource_error}');
                    $data['datasource_selected'] = 'pdo';
                } else {
                    $filename                 = __DIR__ . '/../../../config/authsources.php';
                    include($filename);
                    $config['sql_datasource'] = array(
                        'sqlauth:SQL',
                        'dsn'      => $dsn,
                        'username' => $username,
                        'password' => $password,
                        'query'    => ''
                    );
                    if (array_key_exists('ldap_datasource', $config)) {
                        unset($config['ldap_datasource']);
                    }
                    $res2 = @file_put_contents($filename, '<?php  $config = ' . var_export($config, 1) . "; ?>");
                    
            overwriteAuthsources ();        

            if (!$res2) {
                        $data['errors'][]            = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step2_contact_save_error}');
                        $data['errors'][]            = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step2_contact_save_error2}') . " <i>" . realpath($filename) . "</i>";
                        $data['datasource_selected'] = 'pdo';
                    }
                }
                return true;
            }
        }
    }
    $data['errors'][] = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step5_datasource_request_error}');
    return true;
}
