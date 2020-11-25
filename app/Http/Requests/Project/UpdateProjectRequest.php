<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2020. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\Http\Requests\Project;

use App\Http\Requests\Request;
use App\Models\Project;
use App\Utils\Traits\ChecksEntityStatus;

class UpdateProjectRequest extends Request
{
    use ChecksEntityStatus;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return auth()->user()->can('edit', $this->project);
    }

    public function rules()
    {
        $rules = [];
        
        if (isset($this->number)) {
            $rules['number'] = Rule::unique('projects')->where('company_id', auth()->user()->company()->id)->ignore($this->project->id);
        }

        return $this->globalRules($rules);
    }

    protected function prepareForValidation()
    {
        $input = $this->decodePrimaryKeys($this->all());

        if (isset($input['client_id'])) {
            unset($input['client_id']);
        }

        $this->replace($input);
    }
}
