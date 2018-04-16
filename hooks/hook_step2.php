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
 * Paso 2 del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */


/**
 * Hook a ejecutar antes del paso 2 de la instalación
 * 1) Define la contraseña de administrador (se debe preguntar al usuario)
 * 2) Define el grano de sal (automáticamente)
 * 3) Define información sobre el contacto técnico(se debe preguntar al usuario)
 * 4) Define el idioma por defecto de Castellano(automáticamente)
 * 5) Define la zona horaria por defectosegún el PHP.ini(automáticamente, pero se informa al usuario)
 *
 * @param array &$data  Los datos a utilizar por las plantillas de tipo stepn
 */
function idpinstaller_hook_step2(&$data) {
    return true;
}
