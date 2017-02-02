<?php
/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2016 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\Controller\Admin;

use Alchemy\Phrasea\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubdefsController extends Controller
{
    /**
     * @param int $sbas_id
     * @return Response
     */
    function indexAction($sbas_id) {
        $databox = $this->findDataboxById((int) $sbas_id);

        $config = array(
            "image" => array(
                "definitions" => array(
                    "JPG"       => null,
                    "250px JPG" => array("250", "250", "yes", "yes", "75", "jpeg", ["all"]),
                    "320px JPG" => array("320", "250", "yes", "yes", "75", "jpeg", ["all"]),
                    "480px JPG" => array("480", "250", "yes", "yes", "75", "jpeg", ["all"]),
                    "800px JPG" => array("800", "250", "yes", "yes", "75", "jpeg", ["all"]),
                    "1280px JPG" => array("1280", "250", "yes", "yes", "75", "jpeg", ["all"]),
                    "2048px JPG" => array("2048", "250", "yes", "yes", "75", "jpeg", ["all"]),

                    "PNG"        => null,
                    "250px PNG 8 bits" => array("250", "250", "yes", "yes", "75", "png", ["all"]),
                    "320px PNG 8 bits" => array("320", "250", "yes", "yes", "75", "png", ["all"]),
                    "480px PNG 8 bits" => array("480", "250", "yes", "yes", "75", "png", ["all"]),
                    "800px PNG 8 bits" => array("800", "250", "yes", "yes", "75", "png", ["all"]),
                    "1280px PNG 8 bits" => array("1280", "250", "yes", "yes", "75", "png", ["all"]),
                    "2048px PNG 8 bits" => array("2048", "250", "yes", "yes", "75", "png", ["all"]),

                    "TIFF"              => null,
                    "800px TIFF" => array("800", "250", "yes", "yes", "75", "tiff", ["all"]),
                    "1280px TIFF" => array("1280", "250", "yes", "yes", "75", "tiff", ["all"]),
                    "2048px TIFF" => array("2048", "250", "yes", "yes", "75", "tiff", ["all"]),
                ),

                "form" => array(
                    "size" => "slide",
                    "resolution" => "slide",
                    "strip" => "radio",
                    "flatten" => "radio",
                    "quality" => "slide",
                    "icodec" => "select",
                    "devices" => "checkbox",
                ),
            ),
            "video" => array(
                "definitions" => array(
                    "H264"                              => null,
                    "144P H264 128 kbps ACC 128kbps"    => array("128", "44100", "128", "10", "256", "25", "libx264", "libfaac", ["all"]),
                    "240P H264 256 kbps ACC 128kbps"    => array("128", "44100", "256", "10", "426", "25", "libx264", "libfaac", ["all"]),
                    "360P H264 576 kbps ACC 128kbps"    => array("128", "44100", "576", "10", "480", "25", "libx264", "libfaac", ["all"]),
                    "480P H264 750 kbps ACC 128kbps"    => array("128", "44100", "750", "10", "854", "25", "libx264", "libfaac", ["all"]),
                    "720P H264 1492 kbps ACC 128kbps"    => array("128", "44100", "1492", "10", "1280", "25", "libx264", "libfaac", ["all"]),
                    "1080P H264 2420 kbps ACC 128kbps"    => array("128", "44100", "2420", "10", "1920", "25", "libx264", "libfaac", ["all"]),
                    "WEBM"                              => null,
                    "144P webm 128 kbps ACC 128kbps"    => array("128", "44100", "128", "10", "256", "25", "libvpx", "libfaac", ["all"]),
                    "240P webm 256 kbps ACC 128kbps"    => array("128", "44100", "256", "10", "426", "25", "libvpx", "libfaac", ["all"]),
                    "360P webm 576 kbps ACC 128kbps"    => array("128", "44100", "576", "10", "480", "25", "libvpx", "libfaac", ["all"]),
                    "480P webm 750 kbps ACC 128kbps"    => array("128", "44100", "750", "10", "854", "25", "libvpx", "libfaac", ["all"]),
                    "720P webm 1492 kbps ACC 128kbps"    => array("128", "44100", "1492", "10", "1280", "25", "libvpx", "libfaac", ["all"]),
                    "1080P webm 2420 kbps ACC 128kbps"    => array("128", "44100", "2420", "10", "1920", "25", "libvpx", "libfaac", ["all"]),
                ),

                "form" => array(
                    "audiobitrate" => "slide",
                    "audiosamplerate" => "select",
                    "bitrate" => "slide",
                    "GOPsize" => "slide",
                    "size" => "slide",
                    "fps" => "slide",
                    "vcodec" => "select",
                    "acodec" => "select",
                    "devices" => "checkbox",
                ),
            ),
            /**/"gif" => array(
                "definitions" => array(
                    "low_G"    => array("800", "75", "no", "no", "20", "jpeg", "120", ["tv"]),
                    "medium_G" => array("1200", "150", "yes", "no", "60", "png", "250", ["screen", "handheld"]),
                    "high_G" => array("1500", "220", "yes", "yes", "90", "tiff", "420", ["all"]),
                ),

                "form" => array(
                    "size" => "slide",
                    "resolution" => "slide",
                    "strip" => "radio",
                    "flatten" => "radio",
                    "quality" => "slide",
                    "icodec" => "select",
                    "delay" => "slide",
                    "devices" => "checkbox",
                ),
            ),
            "audio" => array(
                "definitions" => array(
                    "Low AAC 96 kbit/s"    => array("96", "48000", "libmp3lame", ["all"]),
                    "Normal AAC 128 kbit/s" => array("128", "48000", "libmp3lame", ["all"]),
                    "High AAC 320 kbit/s" => array("320", "48000", "libmp3lame", ["all"]),
                ),

                "form" => array(
                    "audiobitrate" => "slide",
                    "audiosamplerate" => "select",
                    "acodec" => "select",
                    "devices" => "checkbox",
                ),
            ),
            "flexpaper" => array(
                "definitions" => array(
                    "low_F"    => array(),
                    "medium_F" => array(),
                    "high_F" => array(),
                ),

                "form" => array(
                ),
            ),
        );

        return $this->render('admin/subdefs.html.twig', [
            'databox' => $databox,
            'subdefs' => $databox->get_subdef_structure(),
            'config'  => $config
        ]);
    }

    /**
     * @param Request $request
     * @param int     $sbas_id
     * @return Response
     * @throws \Exception
     */
    function changeSubdefsAction(Request $request, $sbas_id) {
        $delete_subdef = $request->request->get('delete_subdef');
        $toadd_subdef = $request->request->get('add_subdef');
        $Parmsubdefs = $request->request->get('subdefs', []);

        $databox = $this->findDataboxById((int) $sbas_id);

        $add_subdef = ['class' => null, 'name'  => null, 'group' => null];
        foreach ($add_subdef as $k => $v) {
            if (!isset($toadd_subdef[$k]) || trim($toadd_subdef[$k]) === '') {
                unset($add_subdef[$k]);
            } else {
                $add_subdef[$k] = $toadd_subdef[$k];
            }
        }

        if ($delete_subdef) {
            $delete_subef = explode('_', $delete_subdef, 2);
            $group = $delete_subef[0];
            $name = $delete_subef[1];
            $subdefs = $databox->get_subdef_structure();
            $subdefs->delete_subdef($group, $name);
        } elseif (count($add_subdef) === 3) {
            $subdefs = $databox->get_subdef_structure();

            $group = $add_subdef['group'];
            /** @var \unicode $unicode */
            $unicode = $this->app['unicode'];
            $name = $unicode->remove_nonazAZ09($add_subdef['name'], false);
            $class = $add_subdef['class'];

            $subdefs->add_subdef($group, $name, $class);
        } else {
            $subdefs = $databox->get_subdef_structure();

            foreach ($Parmsubdefs as $post_sub) {
                $options = [];

                $post_sub_ex = explode('_', $post_sub, 2);

                $group = $post_sub_ex[0];
                $name = $post_sub_ex[1];

                $class = $request->request->get($post_sub . '_class');
                $downloadable = $request->request->get($post_sub . '_downloadable');

                $defaults = ['path', 'meta', 'mediatype'];

                foreach ($defaults as $def) {
                    $parm_loc = $request->request->get($post_sub . '_' . $def);

                    if ($def == 'meta' && !$parm_loc) {
                        $parm_loc = "no";
                    }

                    $options[$def] = $parm_loc;
                }

                $mediatype = $request->request->get($post_sub . '_mediatype');
                $media = $request->request->get($post_sub . '_' . $mediatype, []);

                foreach ($media as $option => $value) {

                    if ($option == 'resolution' && $mediatype == 'image') {
                        $option = 'dpi';
                    }

                    $options[$option] = $value;
                }

                $labels = $request->request->get($post_sub . '_label', []);

                $subdefs->set_subdef($group, $name, $class, $downloadable, $options, $labels);
            }
        }

        return $this->app->redirectPath('admin_subdefs_subdef', [
            'sbas_id' => $databox->get_sbas_id(),
        ]);
    }
}
