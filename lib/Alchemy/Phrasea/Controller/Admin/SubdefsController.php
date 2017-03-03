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
                    "low_I"    => array("250", "75", "no", "no", "25", "tiff", ["print"]),
                    "medium_I" => array("500", "150", "no", "yes", "50", "png", ["screen", "print"]),
                    "high_I" => array("750", "250", "yes", "yes", "75", "jpeg", ["all"]),
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
                    "low_V"    => array("50", "8000", "2000", "20", "200", "20", "libx264", "libfaac", ["screen"]),
                    "medium_V" => array("120", "50000", "7500", "140", "600", "100", "libvpx", "libmp3lame", ["print", "projection"]),
                    "high_V" => array("200", "96000", "10000", "180", "1100", "150", "libtheora", "libvorbis", ["all"]),
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
            "gif" => array(
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
                    "low_A"    => array("100", "8000", "libmp3lame", ["tv"]),
                    "medium_A" => array("180", "44100", "flac", ["screen, print"]),
                    "high_A" => array("230", "50000", "libmp3lame", ["all"]),
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
            'config' => $config
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
