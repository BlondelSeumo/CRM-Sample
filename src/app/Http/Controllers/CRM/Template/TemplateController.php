<?php

    namespace App\Http\Controllers\CRM\Template;

    use App\Filters\CRM\TemplateFilter;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\CRM\Template\TemplateRequest;
    use App\Models\CRM\Template\Template;
    use App\Services\CRM\Template\TemplateService;

    class TemplateController extends Controller
    {
        public function __construct(TemplateService $service, TemplateFilter $templateFilter)
        {
            $this->service = $service;
            $this->filter = $templateFilter;
        }

        public function index()
        {
            return $this->service
                ->with([
                    'CreatedBy:id,first_name,last_name',
                ])
                ->filters($this->filter)
                ->paginate(
                    request('per_page', '15')
                );
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            return view('crm.proposals.add-template');
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function store(TemplateRequest $request)
        {
            $template = $this->service->save();

            return created_responses('template', ['template' => $template]);
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            return $this->service->find($id);
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            return view('crm.proposals.add-template', compact('id'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function update(Template $template, TemplateRequest $request)
        {
            $template->update($request->all());
            return updated_responses('template', ['template' => $template]);
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function destroy(Template $template)
        {
            $template->delete();

            return deleted_responses('template', ['template' => $template]);
        }
    }
