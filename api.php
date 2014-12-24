<?php

/**
 * Type
 *
 * @package Boots
 * @subpackage Type
 * @version 1.0.0
 * @license GPLv2
 *
 * Boots - The missing WordPress framework. http://wpboots.com
 *
 * Copyright (C) <2014>  <M. Kamal Khan>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 */

// see http://codex.wordpress.org/Function_Reference/register_post_type

class Boots_Type
{
    //private $Boots;
    //private $Settings;

    private $Types = array();
    private $id = null;

    public function __construct($Boots, $Args, $dir, $url)
    {
        //$this->Boots = $Boots;
        //$this->Settings = $Args;
    }

    private function auto_labels($plural, $singular)
    {
        $singular = $singular !== false ? $singular : $this->id;
        $plural = $plural !== true ? $plural : ($singular . 's');
        $uc_singular = ucwords($singular);
        $uc_plural = ucwords($plural);

        $Labels = array(
            'name' => $uc_plural,
            'singular_name' => $uc_singular,
            'menu_name' => $uc_plural,
            'name_admin_bar' => $uc_singular,
            'all_items' => _x('All', $uc_plural) . ' ' . $uc_plural,
            'add_new' => _x('Add New', $singular),
            'add_new_item' => _x('Add New', $uc_singular) . ' ' . $uc_singular,
            'edit_item' => _x('Edit', $uc_singular) . ' ' . $uc_singular,
            'new_item' => _x('New', $uc_singular) . ' ' . $uc_singular,
            'view_item' => _x('View', $uc_singular) . ' ' . $uc_singular,
            'search_items' => _x('Search', $uc_plural) . ' ' . $uc_plural,
            'not_found' => sprintf(_x('No %d found', $plural), $plural),
            'not_found_in_trash' => sprintf(_x('No %d found in Trash', $plural), $plural),
            'parent_item' => _x('Parent', $uc_singular) . ' ' . $uc_singular,
            'parent_item_colon' => _x('Parent', $uc_singular) . ' ' . $uc_singular . ':',
            'archive_title' => $uc_plural
        );

        $this->set('label', $uc_plural);
        $this->set('labels', $Labels);
        $this->set('rewrite', array(
            'slug' => $plural,
            'with_front' => true
        ));
    }

    // $type: max. 20 characters, can not contain capital letters or spaces
    // $auto_labels: false or (true or plural form in lowercase)
    // $singular: false or singular form in lowercase
    public function create($type, $auto_labels = true, $singular = false)
    {
        $this->Types[$type] = array();

        $this->id = $type;

        if($auto_labels)
        {
            $this->auto_labels($auto_labels, $singular);
        }

        return $this;
    }

    public function set($term, $Args)
    {
        if(!$this->id)
        {
            // notice that the custom post type was not registered.
            // please use Type::register($type)
            return false;
        }

        $this->Types[$this->id][$term] = $Args;
        return $this;
    }

    public function register($public = true)
    {
        $this->set('public', $public);

        register_post_type($this->id, $this->Types[$this->id]);

        $this->id = null;
        return $this;
    }

    // alias of register
    public function done($public = true)
    {
        $this->register($public);
    }
}





