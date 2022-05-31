<?php

// -- BEGIN LICENSE BLOCK ----------------------------------
//
// This file is part of dotSlick, a plugin for Dotclear 2.
// 
// Copyright (c) 2019 Bruno Avet
// Licensed under the GPL version 2.0 license.
// A copy of this license is available in LICENSE file or at
// http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//
// -- END LICENSE BLOCK ------------------------------------


class dsMedia extends dcMedia {

    protected $dir_url;

    public function __construct($core, $dir, $type = '') {
        parent::__construct($core, $type);
        $this->chdir($dir);
    }

    public function fullXMLTree($format = "xmlTag") {
        $count = 0;
        $treecount = 0;
        $xml = $this->dirToXML($count, $treecount);
        $xml->count = $count;
        $xml->count = $treecount;
        if ($format === "xmlTag") {
            return $xml;
        } else if ($format === "text") {
            return $xml->toXML(false);
        }
    }

    public function chdir($dir) {
        try {
            parent::chdir($dir);
            $this->dir_url = $dir;
        } catch (Exception $ex) {
            throw new Exception("dsMedia error for «" . $dir . "»: " . $ex->getMessage());
        }
    }

    public function getDir($type = null) {
        try {
            parent::getDir($type);
        } catch (Exception $ex) {
            throw new Exception("dsMedia error for " . $this->getPwd() . ": " . $ex->getMessage());
        }
    }

    //Removes the server part in the URL
    private function localMediaUrl($url) {
        $pos = strpos($url, html::sanitizeURL($this->core->blog->settings->system->public_url));
        $res = substr($url, $pos);
        return $res;
    }

    private function localMediaPath($url) {
        $res = str_replace($this->core->blog->settings->system->public_url, "", $url);
        return $res;
    }

    private function url2fsPath($url) {
        return path::real($this->core->blog->public_path . '/' . $this->localMediaPath($this->localMediaUrl($url)), true);
    }

    private function fsPath2rel($fspath) {
        return substr(str_replace(path::real($this->core->blog->public_path . '/'), '', $fspath), 1);
    }

    /**
     * Turns a fileItem object to a XmlTag with attributes
     * 
     * 
     *   
     * @param fileItem   $f 
     * 
     * @return XmlTag   a <file> xml object with the following attributes :
     * * dir:       the dir containing the file's url
     * * fname:     the file name
     * * icon :     the file's icone (square format)
     * * title :    the media's title
     * * url   :    http based address for the file
     * * realpath:  the filesystem path to the file
     * * thumbs :   a collection of thumb objects,
     * 
     * * <thumb> :
     * * size:      (s|m|sq|o)
     * * src:       http based url for the thumbnail
     * * realpath:  the filesystem path to the file
     */
    private function fileItem2Xml($f) {
        $xFile = new XmlTag("file");
        $xFile->dir = $this->dir_url;
        $xFile->fname = $f->basename;
        $xFile->title = $f->media_title;
        $xFile->realpath = $this->url2fsPath($f->file_url);
        $xFile->url = $f->file_url;

        foreach ($f->media_thumb as $s => $t) {
            $xThumb = new xmlTag("thumb");
            $xThumb->size = $s;
            $xThumb->src = $t;
            $xThumb->realpath = $this->url2fsPath($t);
            $xFile->insertNode($xThumb);
            if ($s == "sq") {
                $xFile->icon = $t;
            }
        }

        return $xFile;
    }

    /**
     * @name dirItem2Xml
     * 
     * @param {fileitem} $d - a directory record
     * @param {boolean} [$up=false] - specifies if this is a .. directory
     * 
     * @return {xmlTag} <dir></dir> with the following attributes :
     * * dirname:   the dir name
     * * icon :     the dir icon
     * * title :    the dir' title
     * * url   :    http based address for the file
     * * realpath:  the filesystem path to the file
     * * count:     the number of images in the dir
     * * treecount: the number of images in the dir and its subdirectories
     */
    private function dirItem2Xml($d) {
        //F(__METHOD__, $d,$up);
        $u = ($d->parent ? '-up' : '');
        $xDir = new xmlTag("dir");
        $xDir->class = 'media-folder' . $u;
        $xDir->icon = DC_ADMIN_URL . 'images/media/folder' . $u . '.png';
        if ($d->parent) {
            $title = __('Parent folder');
        } else {
            $title = $d->basename;
        }
        $url = $d->relname;
        $xDir->url = $url;
        $xDir->realpath = $this->url2fsPath($d->file_url);
        $xDir->title = $title;
        $xDir->dirname = $d->basename;

        //return R(__METHOD__.print_r($xDir,true),$xDir,true);
        return $xDir;
    }

    /* takes a [['dir'='dirurl'],['imgurl'='dirurl;img1;img2']] list and
     * generates an xml media list.
     */

    public function listToXML($list, $format = "xmlTag") {
        $xRes = new xmlTag("medias");
        foreach ($list as $item) {
            $fnames = [];
            $isdir = false;
            if (isset($item["dir"])) {
                $isdir = true;
                $dir = join("/", array_slice(explode("/", $item["dir"]), 0, -1));
                if ($dir === $item["dir"]) {
                    $dir = "";
                }
                $fnames[] = join("", array_slice(explode("/", $item["dir"]), -1));
            } elseif (isset($item["imgurl"])) {
                $fnames = explode(";", $item["imgurl"]);
                $dir = array_shift($fnames);
            }


            $this->chdir($dir);
            $this->getDir($this->type);
            foreach ($fnames as $f) {
                if ($isdir) {
                    foreach ($this->dir["dirs"] as $v) {
                        if ($v->basename != $f) {
                            continue;
                        }
                        $xDir = $this->dirItem2Xml($v);
//                        $xDir = new xmlTag("dir");
//                        $xDir->class = "media-folder";
//                        $xDir->icon = DC_ADMIN_URL . "images/media/folder.png";
//                        $xDir->title = $v->basename;
//                        $url = html::sanitizeURL($v->file_url);
//                        $xDir->url = $url;
//                        $xDir->realpath = DC_ROOT . $this->localMediaUrl($url);
                        $countMedia = new dsMedia($this->core, $dir . "/" . $v->basename);
                        $countMedia->getDir($this->type);
                        $xDir->count = count($countMedia->dir["files"]);
                        unset($countMedia);
                        $xDir->treecount = -1;
                        $xRes->insertNode($xDir);
                    }
                } else {
                    foreach ($this->dir["files"] as $v) {
                        if ($v->basename != $f) {
                            continue;
                        }
                        $xFile = $this->fileItem2Xml($v);
//                        $xFile = new XmlTag("file");
//                        $xFile->dir = $this->dir_url;
//                        $xFile->fname = $v->basename;
//                        $xFile->title = $v->media_title;
//                        $url = $v->file_url;
//                        $xFile->url = $url;
//                        $xFile->realpath = DC_ROOT . $this->localMediaUrl($url);
//                        foreach ($v->media_thumb as $s => $t) {
//                            $xThumb = new xmlTag("thumb");
//                            $xThumb->size = $s;
//                            $xThumb->src = $t;
//                            $xThumb->realpath = DC_ROOT . $this->localMediaUrl($t);
//                            $xFile->insertNode($xThumb);
//                        }
                        $xRes->insertNode($xFile);
                    }
                }
            }
        }
        if ($format === "xmlTag") {
            return $xRes;
        } else if ($format === "text") {
            return $xRes->toXML(false);
        }
    }

    public function listToImage($list, $height, &$count, $foldersasthumbs = true, $prefix='',$thumbnails=false) {
        $xlist = $this->listToXML($list, "text");
        $xmedias = new SimpleXMLElement($xlist);

        $filenames = [];
        $count=0;

        foreach ($xmedias->xpath('/medias/file | /medias/dir') as $media) {
            $mattr = $media->attributes();
            if ($media->getName() == 'file') {
                if($thumbnails){
                    $thumb = $media->xpath('thumb[@size="t"]');
                    $height = 100;
                }else{
                    $thumb = $media->xpath('thumb[@size="m"]');
                }
                if (count($thumb) == 1) {
                    $attr = $thumb[0]->attributes()->{"realpath"};
                    $filenames[] = (string) $attr;
                } else {
                    $filenames[] = (string) $mattr->{"realpath"};
                }
                $count++;
            } else {
                if($foldersasthumbs){
                    $filenames[] = [];
                }
                $this->chdir($mattr->{"url"});
                $this->getDir("image");
                foreach ($this->dir["files"] as $k => $v) {
                    if($foldersasthumbs){
                        $filenames[count($filenames) - 1][] = $this->url2fsPath($v->media_thumb["sq"]);
                    }else{
                        $filenames[]=$this->url2fsPath($v->media_thumb["m"]);
                    }
                    $count++;
                }
            }
        }

        function resize (&$im,$w,$h,$thumbnails){
            if($thumbnails){
                $im->thumbnailimage($w, $h);
            }else{
                $im->resizeimage($w, $h,1,imagick::FILTER_LANCZOS);
            }
        }

        try {
            $im = new Imagick();
            $im->setresolution(150, 150);

            $pos = strpos(realpath("."), "/dotclear/admin");
            $path = substr(realpath("."), 0, $pos) . "/";

            $uniquefprefix = "/cache/thumbs" . $prefix."-". rand();
            $outfilename_realpath = $this->core->plugins->moduleRoot("dotSlick") . '/' . $uniquefprefix;
            $outfilename = dcPage::getPF("dotSlick/$uniquefprefix.jpg");


            array_map('unlink', glob($this->core->plugins->moduleRoot("dotSlick") . '/cache/thumbs'.$prefix.'*'));


            foreach ($filenames as $f) {
                if (is_array($f)) {
                    $foldername = $this->fsPath2rel(substr($f[0], 0, strrpos($f[0], '/', -1)));

                    $width = 0;

                    $imagefolder = new Imagick();
                    $imagefolder->setresolution(150, 150);
                    $imgthumbs = new Imagick();
                    $imgthumbs->setresolution(150, 150);
                    $j = 0;
                    $imgcol = new Imagick();
                    $imgcol->setresolution(150, 150);
                    $colcount = 0;

                    for ($i = 0; $i < count($f); $i++) {

                        $imgcol->readimage($f[$i]);
                        $imgcol->thumbnailimage(0, $height * 0.19);

                        $last = ($i == (count($f) - 1));
                        $colcount++;

                        if ($last || $colcount === 4) {

                            $imgcol->resetiterator();
                            $imgthumbs->addImage($imgcol->appendimages(true));
                            $width += $imgcol->getimagewidth();
                            $j++;
                        }
                        if ($colcount === 4) {
                            $imgcol = new Imagick();
                            $imgcol->setresolution(150, 150);
                            $colcount = 0;
                        }
                    }
                    $bg = new Imagick($this->core->plugins->moduleRoot("dotSlick") . "/img/folder.svg");
                    $bg->setresolution(150, 150);
                    resize($bg,$width+10,$height,$thumbnails);

                    $label = new Imagick();
                    $label->newImage($width, 10, new ImagickPixel('#ffffff00'));
                    $draw = new ImagickDraw();

                    $draw->setFillColor('#447bb4');
                    $draw->settextantialias(true);
                    $draw->setFontSize(9);
                    $draw->setGravity(Imagick::GRAVITY_CENTER);
                    $draw->annotation(0, 0, $foldername);

                    $label->drawImage($draw);

                    $imgthumbs->resetiterator();
                    $imagefolder->addimage($imgthumbs->appendimages(false));

                    $bg->compositeimage($label, imagick::COMPOSITE_OVER, 5, 17);
                    $bg->compositeimage($imagefolder, Imagick::COMPOSITE_OVER, 5, $height * 0.22);
                    $im->setresolution(150, 150);
                    $im->addImage($bg);
                    $bg->setformat("jpg");
                } else {
                    $im->readImage($f);
                    resize($im, 0, $height, $thumbnails);
                }
            }

            $im->resetIterator();
            $canvas = $im->appendimages(false);
            $canvas->setresolution(150, 150);
            $canvas->setImageFormat("jpg");

            $filename = $outfilename_realpath . ".jpg";
            $canvas->setimagecompression(Imagick::COMPRESSION_JPEG);
            $canvas->setimagecompressionquality(75);
            $canvas->writeimage($filename);
        } catch (ImagickException $e) {
            throw new Exception("Exception imagick " . print_r($e, true));
        }
        return $outfilename;
    }

    public function listDir() {
        $res = [];
        foreach ($this->dir["dirs"] as $d) {
            if ($d->parent) {
                $res[] = '[..]';
            } else {
                $res[] = "[" . $d->basename . "]";
            }
        }
        foreach ($this->dir["files"] as $f) {
            $res[] = $f->basename;
        }
        return $res;
    }

    public function dirToXML(&$fcount = 0, &$treecount = 0, $depth = 0) {

        if ($depth > 20) {
            return;
        }
        global $instance;
        if ($depth == 0) {
            $xRes = new xmlTag("medias");
        } else {
            $xRes = new xmlTag("content");
            $xRes->path = $this->dir_url;
        }

        $this->getDir($this->type);



        /* Insertion of parent dir */
//        if ($this->dir_url != "" && $this->dir_url != "/") {
//            $f=new fileItem($this->dir_url."/..",$this->root, $this->root_url);
//            $xPDir = $this->dirItem2Xml($f, true);
//            $xPDir = new xmlTag("dir");
//            $xPDir->class = "media-folder-up";
//            $xPDir->icon = DC_ADMIN_URL . "/images/media/folder-up.png";
//            $pUrl = explode("/", $this->dir_url);
//            array_splice($pUrl, -1, 1);
//            $parent_url = join("/", $pUrl);
//            $xPDir->url = $parent_url;
//            $xPDir->title = __("Parent folder");
//        }

        $xDirs = [];

        foreach ($this->dir["dirs"] as $k => $v) {
//            if ($v->parent) {
//                continue;
//            }
            if ($v->parent) {
                
            } else {
                
            }
            $xDir = $this->dirItem2Xml($v);

            $count = 0;
            $localtreecount = 0;

            if (!$v->parent) {
                $dsMedia = new dsMedia($this->core, $v->relname, $this->type);

                $filelist = $dsMedia->dirToXML($count, $localtreecount, ++$depth);
                $xDir->insertNode($filelist);
                unset($dsMedia);
                if ($localtreecount == 0) {
                    continue;
                }

                $xDir->count = $count;
                $xDir->treecount = $localtreecount;
            }
            $xDirs[] = $xDir;
            $treecount += $localtreecount;
        }

        $xFiles = [];

        foreach ($this->dir["files"] as $k => $v) {
            $xFile = $this->fileItem2Xml($v);
            $xFiles[] = $xFile;
            $fcount++;
        }
        $treecount += $fcount;

        foreach ($xDirs as $xD) {
            $xRes->insertNode($xD);
        }
        foreach ($xFiles as $xF) {
            $xRes->insertNode($xF);
        }

        return $xRes;
    }

}
