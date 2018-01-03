<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Seat\Web\Http\Controllers\Controller;
use Seat\Kassie\Calendar\Models\Tag;


class TagController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:7',
            'bg_color' => [
                'required',
                'regex:^#(?:[0-9a-fA-F]{3}){1,2}$^'
            ],
            'text_color' => [
                'required',
                'regex:^#(?:[0-9a-fA-F]{3}){1,2}$^'
            ],
            'order' => 'required',
            'quantifier' => 'required|numeric',
            'analytics' => 'required|in:strategic,pvp,mining,other,untracked',
            'tag_id' => 'numeric',
        ]);

        $tag = new Tag($request->all());

        if (!is_null($request->input('tag_id'))) {
            $tag = Tag::find( $request->input('tag_id'));
            $tag->fill($request->all());
        }

        $tag->save();

        return redirect()
            ->back()
            ->with('success', sprintf('The tag "%s" has been successfully created.', $tag->name));
    }

    public function delete(Request $request)
    {
        $tag = Tag::find($request->tag_id);
        if ($tag != null) {
            Tag::destroy($tag->id);
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function get(int $tag_id)
    {
        $tag = Tag::find($tag_id);

        if (is_null($tag))
            return response()->json(['msg' => sprintf('Unable to retrieve tag %s', $tag_id)], 404);

        return response()->json($tag);
    }

}
