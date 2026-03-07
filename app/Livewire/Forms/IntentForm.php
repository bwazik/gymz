<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class IntentForm extends Form
{
    public $gym_id = '';
    public $workout_category_id = '';
    public $workout_target_id = '';
    public $start_time = '';
    public bool $has_invitation = false;
    public $note = '';

    public function rules(): array
    {
        return [
            'gym_id' => 'required|integer|exists:gyms,id',
            'workout_category_id' => 'required|integer|exists:workout_categories,id',
            'workout_target_id' => 'required|integer|exists:workout_targets,id',
            'start_time' => 'required|date|after:now',
            'has_invitation' => 'boolean',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'gym_id.required' => 'لازم تختار الجيم',
            'gym_id.integer' => 'الجيم مش صح',
            'gym_id.exists' => 'الجيم ده مش موجود',
            'workout_category_id.required' => 'لازم تختار نوع التمرين',
            'workout_category_id.integer' => 'نوع التمرين مش صح',
            'workout_category_id.exists' => 'نوع التمرين ده مش موجود',
            'workout_target_id.required' => 'لازم تختار العضلة',
            'workout_target_id.integer' => 'العضلة مش صح',
            'workout_target_id.exists' => 'العضلة دي مش موجودة',
            'start_time.required' => 'لازم تحدد ميعاد التمرين',
            'start_time.date' => 'الميعاد مش صح',
            'start_time.after' => 'الميعاد لازم يكون في المستقبل',
            'note.max' => 'الملاحظات لازم تكون أقل من ٢٥٥ حرف',
        ];
    }
}
