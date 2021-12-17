#!/bin/bash -p
#
# dispatcher.sh
#
# This script invokes sas with a the default configuration for this
# application server. It changes directories so that sas is invoked
# from the root directory of this application server.
#

# Uncomment the set -x to run in debug mode
set -x

scriptpath="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/dispatcher.sh"
export SASCONFIG=/opt/sas/viya/config

# Set up locale
if [ -f /etc/profile.d/lang.sh ]; then
    . /etc/profile.d/lang.sh
fi

sas_java_path="${SASCONFIG}/etc/sysconfig/sas-javaesntl/sas-java"

if [[ -f "$sas_java_path" ]]; then
   echo "sourcing $sas_java_path"
   . "$sas_java_path"
   echo ""
fi

# Configuration for a TLS enabled CAS controller
CAS_CLIENT_SSL_CA_LIST="${SASCONFIG}/etc/SASSecurityCertificateFramework/cacerts/trustedcerts.pem"
export CAS_CLIENT_SSL_CA_LIST

# Configuration for SAS Access
export LD_LIBRARY_PATH=/opt/sas/viya/home/lib64/accessclients/lib:/usr/lib64/mysql/:$LD_LIBRARY_PATH
export ODBCSYSINI=/opt/sas/viya/home/lib64/accessclients/
export ODBCINI=/opt/sas/viya/home/lib64/accessclients/odbc.ini
export ODBCINST=/opt/sas/viya/home/lib64/accessclients/odbcinst.ini
export ODBCHOME=/opt/sas/viya/home/lib64/accessclients


# Call SAS using the dispatcher.sas program. This file must be present in the same dir as this script
Quoteme() {
   if [ $# -gt 1 ]; then
      quoteme="\"$*\""
   else
      quoteme=$1
   fi
}

foundation_home="/opt/sas/spre/home/SASFoundation"
sas_command="${foundation_home}/sas"
cmd="$sas_command"
for arg in "$@" ; do
   Quoteme $arg
   tmp="$quoteme"
   cmd="$cmd $tmp"
done
now=$(date +"%D %T.%3N")
echo "start $cmd $now" >> /tmp/exec_php_sas
eval exec $cmd -sysin dispatcher.sas -work /sastmp/saswork -memsize 4G -realmemsize 3G -sortsize 3G -sumsize 3G -bufno 4K -bufsize 64K -utilloc '/sastmp/sascache' -print /dev/null -rsasuser -sasuser /var/tmp

