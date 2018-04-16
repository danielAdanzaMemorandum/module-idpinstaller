#!/bin/bash 

#
#  IdPRef - IdP de Referencia para SIR 2 basado en SimpleSAMLPHP v1.13.1
# ============================================================================ #
#
# Copyright (C) 2014 - 2015 by the Spanish Research and Academic Network.
# This code was developed by Auditoria y Consultoría de Privacidad y Seguridad
# (PRiSE http://www.prise.es) for the RedIRIS SIR service (SIR: 
# http://www.rediris.es/sir)
#
# **************************************************************************** #
#
# Licensed under the Apache License, Version 2.0 (the "License"); you may not
# use this file except in compliance with the License. You may obtain a copy of
# the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
# WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
# License for the specific language governing permissions and limitations under
# the License.
#
# **************************************************************************** #
#
# Shell Script (Bash) para la generación de certificados x509 a partir de los
# parámetros de entrada:
#   $0 => Nombre del comando
#   $1 => Fichero de salida para cert 
#   $2 => Fichero de salida para privKey
#   $3 => Organization
#   $4 => CommonName
# 
# **************************************************************************** #
#
# Developed by: "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
# Copyroght:    Copyright (C) 2014 - 2015 by the Spanish Research and
#               Academic Network.
# License:      http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
# Version:      0.3-Sprint3-R57
#
# **************************************************************************** #


# Procesar el Hostname para obtener todos los RDN DC del subjectDN. Por ejemplo
# de una cadena del tipo www.example.net obtenemos una cadena del tipo:
# /DC=www/DC=example/DC=net
read FULL_DC <<< $( echo $4 |awk '{ 
  n = split($0, t, ".")
  for (i = 0; ++i <= n;)
    out=out"/DC="t[i]
  print out
  }' )

# Nombre de la organización 
O="/O="$3

# Organizational Unit
OU="/OU=Certificado SPT"

# Common Name
CN="/CN="$4

# Tiempo de vida del certificado en días por defecto 10 años.
TTL=3652

# Tamaño de la clave por defecto 1024 bytes
KEY_SIZE=1024

# Composición del SubjectDN del certificado.
SUBJECT=${FULL_DC}${O}${OU}${CN}

# Ruta al binario de OpenSSL. Si se encuentra se aborta la ejecución.
OPENSSL="/opt/local/bin/openssl"
OPENSSL="openssl"
[[ $(type -P "$OPENSSL") ]] && echo "$OPENSSL is in PATH"  || { echo "$OPENSSL is NOT in PATH" 1>&2; exit 1; }

$OPENSSL req -newkey rsa:$KEY_SIZE -new -x509 -days $TTL -nodes -out $1 -keyout $2  -multivalue-rdn -subj "$SUBJECT"
exit $?