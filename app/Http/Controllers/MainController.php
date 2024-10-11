<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {

        $menus = $this->mainMenus();
        // dd($menus);

        // dd($menuTree);
        return view('home',['menuhtml'=>$menus]);
    }
   public function RoutesContents()
   {
    $menus = $this->mainMenus();

    $menuAlias  =   str_replace('/','',request()->getPathInfo());

    $menuContents    =   MenuItem::where('alias',$menuAlias)->first();

    // dd($menuContents);
    if($menuContents->viewtype == 'link')
    {
        return redirect()->away($menuContents->linkaddress);
    }else if($menuContents->viewtype == 'page')
    {
        $html = html_entity_decode($menuContents->pageitem->description);
        $html2 = htmlspecialchars_decode($menuContents->pageitem->description);
        return view('home',['data' => $menuContents,'menuhtml'=>$menus]);
    }else if($menuContents->viewtype == 'category')
    {

    }


    // dd($html2);

   }
    private function buildMenuTree(array $menuItems, $parentId = 0)
    {
        // Filter items that match the current parent ID
        $filtered = array_filter($menuItems, function ($item) use ($parentId) {
            return $item['parent_id'] == $parentId;
        });

        // Sort the filtered items by `menuorderid`
        usort($filtered, function ($a, $b) {
            return $a['menuorderid'] <=> $b['menuorderid'];
        });

        // Loop through the filtered items and build the submenu recursively
        foreach ($filtered as &$item) {
            // Recursively find submenus
            $item['submenu'] = $this->buildMenuTree($menuItems, $item['id']);

            // If the submenu is empty, remove the 'submenu' key
            if (empty($item['submenu'])) {
                unset($item['submenu']);
            }
        }

        return $filtered;
    }

    private function mainMenus()
    {
        $menuCategoryId = '1';
        $menuItems = MenuItem::where('type', $menuCategoryId)
        ->where('state', '1')
        // ->orderBy('menuorderid', 'ASC')
        ->get(['id', 'title', 'alias','linkaddress','target','viewtype','type','parent_id','menuorderid'])->toArray();

        // Build the menu tree
        $menuTree = $this->buildMenuTree($menuItems);
        // dd($menuTree);
        $menuhtml = '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';
        foreach($menuTree as $menu)
        {
            if(isset($menu['submenu']))
            {
                $menuhtml .= '<li class="nav-item dropdown">';
                $menuhtml .= '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                '. $menu['title'] .'
                            </a>';
                $menuhtml .= '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                foreach ($menu['submenu'] as $submenu)
                {
                    if(isset($submenu['submenu']))
                    {
                        $menuhtml .= '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                '. $submenu['title'] .'
                            </a>';
                            $menuhtml .= '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                            foreach($submenu['submenu'] as $subsubmenu)
                            {
                                if($subsubmenu['viewtype'] == 'link')
                                {
                                    $alias = $subsubmenu['linkaddress'];
                                }else{
                                    $alias = $subsubmenu['alias'];
                                }
                                $menuhtml .= '<li><a class="dropdown-item" target="'.$subsubmenu['target'].'" href="'.$alias.'">'.$subsubmenu['title'].'</a></li>';

                            }
                            $menuhtml .= '</ul></li>';
                    }
                    if($submenu['viewtype'] == 'link')
                    {
                        $alias = $submenu['linkaddress'];
                    }else{
                        $alias = $submenu['alias'];
                    }
                    $menuhtml .= '<li><a class="dropdown-item" target="'.$submenu['target'].'" href="'.$alias.'">'.$submenu['title'].'</a></li>';
                }
                $menuhtml .= '</ul></li>';

            }else{
                if($menu['viewtype'] == 'link')
                    {
                        $alias = $menu['linkaddress'];
                    }else{
                        $alias = $menu['alias'];
                    }

                $menuhtml .= '<li class="nav-item">';
                $menuhtml .= '<a class="nav-link" target="'.$menu['target'].'" href="'.$alias.'">'.$menu['title'].'</a>';
                $menuhtml .= '</li>';
            }

        }
        $menuhtml .= "</ul>";
        // dd($menuhtml);
        return $menuhtml;
    }
}
