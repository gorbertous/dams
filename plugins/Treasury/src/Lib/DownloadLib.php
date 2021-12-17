<?php

declare(strict_types=1);

namespace Treasury\Lib;

use KubAT\PhpSimple\HtmlDomParser;

class DownloadLib
{

    public static function download($path)
    {
        $path = normalizer_normalize($path);
        if (strpos($path, '/sas/common/portfolio_analytics') !== false) {
            //   http://vmd-sas-01/damsv2/damsv2ajax/download_file/1?file=/sas/common/portfolio_analytics/Seasonality_analysis_COSME-LGF_21JUL16.xlsx
            error_log("correct path : " . $path);
        } else {
            $path = str_replace('/var/www/html', '', $path);
            $path = "/var/www/html/" . $path;
            error_log("path " . $path . " fixed");
        }
        // required for IE
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');
        $path = str_replace('//', '/', $path);

        if (strpos($path, '..') !== false) {
            error_log("access requested to  : " . $path);
            echo "access is limited to data folder";
            exit();
        }
        //$path = realpath ($path);
        if (strpos($path, '/var/www/html/data/') === false) {
            if (strpos($path, '/app/SAS/common/portfolio_analytics') === false) {
                if (strpos($path, '/sas/common/portfolio_analytics') === false) {
                    // only access to data folder
                    error_log("access requested to  : " . $path);
                    echo "access is limited to data folder";
                    exit();
                }
            }
        }
        $file_extension = strtolower(substr(strrchr($path, "."), 1));
        $file_extension = trim($file_extension, ' ');
        $path = trim($path, ' ');

        if (!file_exists($path)) {
            error_log("DownloadLib : file does not exist : " . $path);
            $path = dirname($path) . "/" . rawurlencode(basename($path));
            if (!file_exists($path)) {
                error_log("DownloadLib : file does not exist : " . $path);
                exit;
            }
        };
        switch ($file_extension) {
            case "pdf": $ctype = "application/pdf";
                break;
            //case "exe": $ctype="application/octet-stream"; break;
            case "exe": exit;
                break;
            case "zip": $ctype = "application/zip";
                break;
            case "doc": $ctype = "application/msword";
                break;
            case "docx": $ctype = "application/msword";
                break;
            case "xls": $ctype = "application/vnd.ms-excel";
                break;
            case "xlsx": $ctype = "application/vnd.ms-excel";
                break;
            case "xml": $ctype = "application/vnd.ms-excel";
                break;
            //case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            //case "gif": $ctype="image/gif"; break;
            //case "png": $ctype="image/png"; break;
            //case "jpeg":
            //case "jpg": $ctype="image/jpg"; break;
            case "ppt":
            case "gif":
            case "png":
            case "jpeg":
            case "jpg": exit;
                break;
            //default: $ctype="application/force-download";
        }
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: $ctype");
        header("Content-Disposition: attachment; filename=\"" . basename($path) . "\";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($path));
        readfile($path);
    }

    public static function change_downloadable_links($html, $folder = "docs")
    {
        if (empty($html)) {
            return '';
        } else {
            $dom = HtmlDomParser::str_get_html($html);

            $links = $dom->find('a');
            $downloadable = ['doc', 'docx', 'pdf', 'xml', 'xls', 'xsl', 'xlsx'];
            foreach ($links as $l) {
                $old_link = normalizer_normalize($l->href);
                $url_exp = explode('/', $old_link);
                $file = $url_exp[count($url_exp) - 1];
                $file_exp = explode('.', $file);
                $ext = trim($file_exp[count($file_exp) - 1]);
                //$ext = trim($ext);
                if (in_array($ext, $downloadable)) {
                    //$new_link = "/ajax/download-file/" . $old_link . $folder;
                    $new_link = "/damsv2/ajax/download-file/" . $file . '/' . $folder;
                    $l->href = $new_link;
                }
            }
            return $dom;
        }
    }
    public static function filter_parameters($params)
    {
		if (empty($params) || !is_array($params))
		{
			return null;
		}
        foreach($params as &$param)
		{
			$param = str_replace('..', '', $param);
			$param = str_replace('//', '/', $param);
		}
		return $params;
    }

}
