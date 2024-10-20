<?php

namespace App\Http\Controllers\Anthaleja\Wiki;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\CommonMarkConverter;
use App\Models\Anthaleja\Wiki\WikiTemplate;
use App\Models\Anthaleja\Wiki\WikiInfoboxTemplate;

class TemplateController extends Controller
{
    public function index()
    {
        try {
            $templates = WikiTemplate::all();
            return view('anthaleja.wiki.templates.index', compact('templates'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error loading templates.']);
        }
    }

    public function create(Request $request)
    {
        return view('anthaleja.wiki.templates.create', [
            'title' => 'Create a New Template',
            'formAction' => route('templates.store'),
            'isEdit' => false,
            'nameValue' => $request->input('name', ''),
            'contentValue' => '',
        ]);
    }

    public function createInfobox()
    {
        return view('anthaleja.wiki.infoboxes.create', [
            'isEdit' => false,
            'formAction' => route('infoboxes.store'),
            'typeValue' => '',
            'contentValue' => '',
            'optionalFields' => [],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required'
        ]);

        $character = Auth::user()->character;
        $converter = new CommonMarkConverter();
        $htmlContent = $converter->convert($request->input('content'));


        $template = new WikiTemplate();
        $template->character_id = $character->id;
        $template->title = $request->title;
        $template->slug = Str::slug($request->title);
        $template->content = $request->input('content');
        $template->html_content = $htmlContent;
        $template->published_at = now();
        $template->save();

        return redirect()->route('templates.index')->with('success', 'Template successfully created!');
    }

    public function storeInfobox(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255|unique:wiki_infobox_templates,type',
            'content' => 'required',
            'optional_fields' => 'nullable|string'
        ]);

        $optionalFields = explode(',', $validated['optional_fields']);

        WikiInfoboxTemplate::create([
            'type' => $validated['type'],
            'content' => $validated['content'],
            'optional_fields' => $optionalFields,
        ]);

        return redirect()->route('infoboxes.index')->with('success', 'Infobox creato con successo!');
    }


    public function edit($id)
    {
        $template = WikiTemplate::findOrFail($id);
        return view('anthaleja.wiki.templates.edit', [
            'title' => 'Edit Template: ' . $template->title,
            'formAction' => route('templates.update', $template->id),
            'isEdit' => true,
            'nameValue' => $template->title,
            'contentValue' => $template->content,
        ]);
    }

    public function editInfobox($id)
    {
        $infobox = WikiInfoboxTemplate::findOrFail($id);
        return view('anthaleja.wiki.infoboxes.create', [
            'isEdit' => true,
            'formAction' => route('infoboxes.update', $infobox->id),
            'typeValue' => $infobox->type,
            'contentValue' => $infobox->content,
            'optionalFields' => $infobox->optional_fields,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:wiki_templates,name,' . $id,
            'content' => 'required',
        ]);

        $template = WikiTemplate::findOrFail($id);
        $template->update($validatedData);

        return redirect()->route('templates.index')->with('success', 'Template successfully updated!');
    }

    public function updateInfobox(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255|unique:wiki_infobox_templates,type,' . $id,
            'content' => 'required',
            'optional_fields' => 'nullable|string'
        ]);

        $optionalFields = explode(',', $validated['optional_fields']);

        $infobox = WikiInfoboxTemplate::findOrFail($id);
        $infobox->update([
            'type' => $validated['type'],
            'content' => $validated['content'],
            'optional_fields' => $optionalFields,
        ]);

        return redirect()->route('infoboxes.index')->with('success', 'Infobox aggiornato con successo!');
    }
}
