/*=====================================================================================================================================================
    DISPATCHER.SAS
=======================================================================================================================================================
    caller                   :   CALLSAS.PHP
    includes                 :   NONE
    location                 :   folder define in APPSTART.SAS
    version                  :   1.0
    Created by               :   sogeti
    Modified by              :   sogeti
    last modification date   :   14/02/2013
======================================================================================================================================================
    COMMENTS:


======================================================================================================================================================
    PARAMETERS (PHP URL):
    FIXE:
        _service = default
        _program = sasprg.dispatcher.sas
        _os = li/wi
    VARIABLE:
        project                         Nom du projet defini dans routing.cong
        sasfile                         Nom du programmer SAS a exécuter
        other                           Paramètre du projet
====================================================================================================================================================*/

/*option de debug*/
options mprint mlogic symbolgen;

/* Configure CAS. This must be changed for each enviroment */
options set=CAS_AUTH_METHOD=authinfo;
options cashost=vmdv.eifaws.com casport=5570;

/* Uncomment CAS-statement below to sign into CAS
   Note that a file .autoinfo must exist in HOME storing userid and password.
   See: https://go.documentation.sas.com/api/docsets/authinfo/9.4/content/authinfo.pdf
*/
/*
cas casauto;
*/

/* SDKTJJ 20210211 - For migration to Viya */
%let _ADMAIL=%nrstr(eifsas-support@eif.org);
%let _SRVNAME=&SYSTCPIPHOSTNAME;
%let _tmpcat="bla";
%let _replay="bla";
filename _webout "&webout" mod;

/************************************************************************************/
/* CONFIGURATION                                                                    */
/************************************************************************************/
/*Répertoire racine serveru Web*/
%let _webhome = /var/www/html/;
/*Répertoire du DISPATCHER = répertoire dans APPSTART*/
%let _sasdisp = sasdisp;
/*Path vers le répertoire de travail*/
%let _saswebhome = &_webhome.&_sasdisp;

/*Répertoire contenant les projets SAS*/
/* TODO : OS doit un paramètre*/
/*Nom du Répertoire racine programme SAS*/
%let project = %lowcase(&project);
%let _sasprodir = sas/;
/*Chemin vers les projets SAS*/
%let _sasprohome = &_webhome.&_sasprodir;
/*Programme SAS pour la présentation des erreurs de dispatching*/ 
%let _sas404 = error.sas;
/*Programme SAS pour la visualisation de la table de routing*/
%let _sasrout = routing.sas;
/* Default Debug Level */
%let _debuglevel = 0;
/************************************************************************************/
/*MACRO DISPATCHER*/
%macro Dispatcher;
    /* Importer le Fichier de routing ROUTING.CONF*/
    /*Ouverture du fichier de config*/
    filename file "&_saswebhome./routing.conf";
    /*Lecture du fichier de configuration*/
    proc import datafile=file out=routing dbms=csv replace;
        getnames=yes;
    run;
    %let _folder=Unknown;
    %let _f=;
    %let _error=FALSE;
    /*si PROJECT = NONE => affichage de la table de routing*/
    %if %bquote(&project) eq none %then %do;
        %include "&_saswebhome./&_sasrout";
    %end;
    %else %do;
        /*Recherche du répertoire du projet (project) dans le fichier de config*/
        data _null_;
            set routing;
            where p_name = "&project";
            call symputx("_f",p_folder);
        run;
        /*si le réperoite existe le fichier de config */
        %if %bquote(&_f) ne Unknown %then %do;
            /*Test de la présence du répertoire dans le filesystem du serveur*/
            /* TODO : Windows => FABRICE*/
            %let _folder = &_sasprohome.&_f;
            /* La commande retourne TRUE si le répertoire existe et FALSE s'il n'existe pas*/
            filename files pipe "if test -d &_folder; then echo 'TRUE'; else echo 'FALSE'; fi";
            /*récupération de la valeur de la ligne de commande*/
            data files_import;
                infile files pad;
                input file $60.;
                call symputx("_direxist",file);
            run;
            /* Si le folder existe dans le FS*/
            %if %bquote(&_direxist) eq TRUE %then %do;
                /*Création du chemin complet avec le nom du programme*/
                %let _inc = &_folder.&sasfile ;
                /*Test de la présence du fichier dans le répertoire dans le filesystem du serveur*/
                /* TODO : Windows => FABRICE*/
                /*La commande retourne TRUE si le fichier existe et FALSE s'il n'existe pas*/
                filename files pipe "if test -e &_inc; then echo 'TRUE'; else echo 'FALSE'; fi";
                /*récupération de la valeur de la ligne de commande*/
                data files_import;
                    infile files pad;
                    input file $60.;
                    call symputx("_prgexist",file);
                run;
                /*Si le fichier n'existe pas dans le FS*/
                %if %bquote(&_prgexist) eq FALSE %then %do;
                    /* erreur de type repertoire*/
                    %let _error=FIL;
                    %let _inc = &_saswebhome./&_sas404;
                %end;
            %end;
            /* le répertoire configuré dans ROUTING.conf n'extiste pas dans le FS*/
            %else %do;
                /* erreur de type repertoire*/
                %let _error=DIR;
                %let _inc = &_saswebhome./&_sas404;
            %end;
        %end;
        %else %do;
            /*Le réperoire n'existe pas = le projet passé en parametre (project) n'est pas dans le fichier de config*/
            %let _error=PRO;
            %let _inc = &_saswebhome./&_sas404;
        %end;

    %include "&_inc.";
	
%end;
%mend;

/*Exécution de la MACRO*/
%Dispatcher;
/*========================================================================================================================================================*/
