<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuitemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd('gg');
        $menuCategoryId = '1';
        $menuItems = MenuItem::where('type', $menuCategoryId)
        ->where('state', '1')
        // ->orderBy('menuorderid', 'ASC')
        ->get(['id', 'title', 'alias','linkaddress','target','viewtype','type','parent_id','menuorderid'])->toArray();

        // Build the menu tree
        $menuTree = $this->buildMenuTree($menuItems);

        return response()->json($menuTree);

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




    // dd($menuItems);

       /* if ($menuItems) {
            $JsonmenuItems = [];

            // First, we process all the menu items to get top-level items (parent_id = 0)
            foreach ($menuItems as $menu) {
                // Only add top-level menus (those with parent_id = 0)
                if ($menu->parent_id == '0') {
                    $JsonmenuItems[] = [
                        'id' => $menu->id,
                        'title' => $menu->title,
                        'alias' => $menu->alias,
                        'linkaddress' => $menu->linkaddress,
                        'target' => $menu->target,
                        'viewtype' => $menu->viewtype,
                        'type' => $menu->type,
                        'parent_id' => $menu->parent_id,
                        'menuorderid' => $menu->menuorderid,
                    ];
                }
            }

            // Now, let's populate the submenus and subsubmenus for each parent menu
            // dd($JsonmenuItems);
            foreach ($JsonmenuItems as &$parentMenu) {
                // dd($parentMenu);
                $submenus = [];
                foreach ($menuItems as $submenu) {
                    // dd(vars: $submenu);
                    // Check if the current item is a child of the current parent menu
                    if ($submenu->parent_id == $parentMenu['id']) {
                        $submenus[] = [
                            'id' => $submenu->id,
                            'title' => $submenu->title,
                            'alias' => $submenu->alias,
                            'linkaddress' => $submenu->linkaddress,
                            'target' => $submenu->target,
                            'viewtype' => $submenu->viewtype,
                            'type' => $submenu->type,
                            'parent_id' => $submenu->parent_id,
                            'menuorderid' => $submenu->menuorderid,
                        ];
                    }
                }

                // Assign the found submenus to the parent menu's 'submenu' key and order them by `menuorderid`
                if (!empty($submenus)) {
                    $parentMenu['submenu'] = $submenus;

                    usort($parentMenu['submenu'], function ($a, $b) {
                        return $a['menuorderid'] - $b['menuorderid'];
                    });


                    // Recursively populate subsubmenus
                    foreach ($parentMenu['submenu'] as &$submenu) {
                        // dd($submenu);
                        $subsubmenus = [];
                        foreach ($menuItems as $subsubmenu) {
                            if ($subsubmenu->parent_id == $submenu['id']) {
                                $subsubmenus[] = [
                                    'id' => $subsubmenu->id,
                                    'title' => $subsubmenu->title,
                                    'alias' => $subsubmenu->alias,
                                    'linkaddress' => $subsubmenu->linkaddress,
                                    'target' => $subsubmenu->target,
                                    'viewtype' => $subsubmenu->viewtype,
                                    'type' => $subsubmenu->type,
                                    'parent_id' => $subsubmenu->parent_id,
                                    'menuorderid' => $subsubmenu->menuorderid,
                                ];
                            }
                        }
                        if (!empty($subsubmenus)) {
                            // dd($subsubmenus);
                            $submenu['submenu'] = $subsubmenus;
                            usort($submenu['submenu'], function ($a, $b) {
                                return $a['menuorderid'] - $b['menuorderid'];
                            });
                        }
                    }
                }
            }
            // dd($JsonmenuItems);
            return response()->json($JsonmenuItems);
        }
        else
        {
            return response()->json(['message'=>'no records available'], 200);
        }*/

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // [
        //     {
        //       "id": 2,
        //       "title": "Contact",
        //       "alias": "contact",
        //       "linkaddress": "https://datastoneglobal.com",
        //       "target": "_self",
        //       "viewtype": "link",
        //       "type": "1",
        //       "parent_id": "0",
        //       "menuorderid": "2",
        //       "submenu": [
        //         {
        //           "id": 4,
        //           "title": "contact us",
        //           "alias": "contact-us",
        //           "linkaddress": "1",
        //           "target": "_self",
        //           "viewtype": "page",
        //           "type": "1",
        //           "parent_id": "2",
        //           "menuorderid": "1"
        //         }
        //       ]
        //     },
        //     {
        //       "id": 1,
        //       "title": "About",
        //       "alias": "about",
        //       "linkaddress": "1",
        //       "target": "_blank",
        //       "viewtype": "page",
        //       "type": "1",
        //       "parent_id": "0",
        //       "menuorderid": "3",
        //       "submenu": [
        //         {
        //           "id": 5,
        //           "title": "Vision & Mission",
        //           "alias": "vision-mission",
        //           "linkaddress": "https://datastoneglobal.com/vision",
        //           "target": "_self",
        //           "viewtype": "link",
        //           "type": "1",
        //           "parent_id": "1",
        //           "menuorderid": "1"
        //         },
        //         {
        //           "id": 3,
        //           "title": "History",
        //           "alias": "history",
        //           "linkaddress": "1",
        //           "target": "_self",
        //           "viewtype": "page",
        //           "type": "1",
        //           "parent_id": "1",
        //           "menuorderid": "2"
        //           "submenu": [
        //             {
        //                 "id": 6,
        //                 "title": "sample history",
        //                 "alias": "sample-history",
        //                 "linkaddress": "https://samplehistory.com",
        //                 "target": "_self",
        //                 "viewtype": "link",
        //                 "type": "1",
        //                 "parent_id": "3",
        //                 "menuorderid": "1"
        //             }
        //           ]
        //         }
        //       ]
        //     },
        //     {
        //       "id": 7,
        //       "title": "Events",
        //       "alias": "events",
        //       "linkaddress": "https://sampleevents.com",
        //       "target": "_self",
        //       "viewtype": "link",
        //       "type": "1",
        //       "parent_id": "0",
        //       "menuorderid": "5"
        //     }
        // ]
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
