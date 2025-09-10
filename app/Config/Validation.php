<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public $contactEmail = [
        'email' => [
            'label'  => 'Correo Electrónico',
            'rules'  => 'required|valid_email'
        ],
        'name' => [
            'label'  => 'Nombre',
            'rules'  => 'required|min_length[3]|max_length[100]'
        ],
        'message' => [
            'label'  => 'Mensaje',
            'rules'  => 'required|min_length[3]|max_length[1000]'
        ],
        'subject' => [
            'label'  => 'Asunto',
            'rules'  => 'required|min_length[3]|max_length[100]',
        ],
        'reCaptcha2' => [
            'label'  => 'Captcha',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Por favor, confirme que no eres un robot.'
            ]
        ]
    ];

    public $experienceStore = [
        'company' => [
            'label'  => 'Compañía',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'specialty_es' => [
            'label'  => 'Especialidad (ES)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'specialty_en' => [
            'label'  => 'Especialidad (EN)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'start' => [
            'label'  => 'Fecha de Inicio',
            'rules'  => 'required|valid_date'
        ],
        'end' => [
            'label'  => 'Fecha de Fin',
            'rules'  => 'required|valid_date'
        ],
        'description_es' => [
            'label'  => 'Descripción (ES)',
            'rules'  => 'required|min_length[3]'
        ],
        'description_en' => [
            'label'  => 'Description (EN)',
            'rules'  => 'required|min_length[3]'
        ],
    ];
    public $experienceUpdate = [
        'id' => [
            'label'  => 'ID',
            'rules'  => 'required|is_natural_no_zero'
        ],
        'company' => [
            'label'  => 'Compañía',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'specialty_es' => [
            'label'  => 'Especialidad (ES)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'specialty_en' => [
            'label'  => 'Especialidad (EN)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'start' => [
            'label'  => 'Fecha de Inicio',
            'rules'  => 'required|valid_date'
        ],
        'end' => [
            'label'  => 'Fecha de Fin',
            'rules'  => 'permit_empty|valid_date'
        ],
        'description_es' => [
            'label'  => 'Descripción (ES)',
            'rules'  => 'required|min_length[3]'
        ],
        'description_en' => [
            'label'  => 'Description (EN)',
            'rules'  => 'required|min_length[3]'
        ],
    ];
    public $profileStore = [
        'name' => [
            'label'  => 'Nombre',
            'rules'  => 'required|min_length[3]|max_length[100]'
        ],
        'surname' => [
            'label'  => 'Apellido',
            'rules'  => 'required|min_length[3]|max_length[100]'
        ],
        'avatar' => [
            'label'  => 'Avatar',
            'rules'  => 'uploaded[avatar]|is_image[avatar]|max_size[avatar,2048]|mime_in[avatar,image/jpg,image/jpeg,image/png]|ext_in[avatar,jpg,jpeg,png]'
        ],
        'email_contact' => [
            'label'  => 'Correo Electrónico',
            'rules'  => 'required|valid_email|is_unique[profiles.email_contact]'
        ],
        'description_es' => [
            'label'  => 'Sobre Mí (ES)',
            'rules'  => 'permit_empty|min_length[3]'
        ],
        'description_en' => [
            'label'  => 'Sobre Mí (EN)',
            'rules'  => 'permit_empty|min_length[3]'
        ],
        'specialty_es' => [
            'label'  => 'Especialidad (ES)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'specialty_en' => [
            'label'  => 'Especialidad (EN)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'language_es' => [
            'label'  => 'Lenguaje (ES)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'language_en' => [
            'label'  => 'Lenguaje (EN)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'github_url' => [
            'label'  => 'GitHub URL',
            'rules'  => 'permit_empty|valid_url|max_length[255]'
        ],
        'linkedin_url' => [
            'label'  => 'LinkedIn URL',
            'rules'  => 'permit_empty|valid_url|max_length[255]'
        ],
        'instagram_url' => [
            'label'  => 'Instagram URL',
            'rules'  => 'permit_empty|valid_url|max_length[255]'
        ]
    ];
    public $profileUpdate = [
        'id' => [
            'label'  => 'ID',
            'rules'  => 'required|is_natural_no_zero'
        ],
        'name' => [
            'label'  => 'Nombre',
            'rules'  => 'required|min_length[3]|max_length[100]'
        ],
        'surname' => [
            'label'  => 'Apellido',
            'rules'  => 'required|min_length[3]|max_length[100]'
        ],
        'avatar' => [
            'label'  => 'Avatar',
            'rules'  => 'is_image[avatar]|max_size[avatar,2048]|mime_in[avatar,image/jpg,image/jpeg,image/png]|ext_in[avatar,jpg,jpeg,png]'
        ],
        'email_contact' => [
            'label'  => 'Correo Electrónico',
            'rules'  => 'required|valid_email|is_unique[profiles.email_contact,id,{id}]'
        ],
        'description_es' => [
            'label'  => 'Sobre Mí (ES)',
            'rules'  => 'permit_empty|min_length[3]'
        ],
        'description_en' => [
            'label'  => 'Sobre Mí (EN)',
            'rules'  => 'permit_empty|min_length[3]'
        ],
        'specialty_es' => [
            'label'  => 'Especialidad (ES)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'specialty_en' => [
            'label'  => 'Especialidad (EN)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'language_es' => [
            'label'  => 'Lenguaje (ES)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'language_en' => [
            'label'  => 'Lenguaje (EN)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'github_url' => [
            'label'  => 'GitHub URL',
            'rules'  => 'permit_empty|valid_url|max_length[255]'
        ],
        'linkedin_url' => [
            'label'  => 'LinkedIn URL',
            'rules'  => 'permit_empty|valid_url|max_length[255]'
        ],
        'instagram_url' => [
            'label'  => 'Instagram URL',
            'rules'  => 'permit_empty|valid_url|max_length[255]'
        ]
    ];
    public $skillStore = [
        'name' => [
            'label'  => 'Nombre',
            'rules'  => 'required|min_length[3]|max_length[100]|is_unique[skills.name]'
        ],
        'percentage' => [
            'label'  => 'Porcentaje',
            'rules'  => 'required|is_natural_no_zero|less_than_equal_to[100]'
        ]
    ];
    public $skillUpdate = [
        'id' => [
            'label'  => 'ID',
            'rules'  => 'required|is_natural_no_zero'
        ],
        'name' => [
            'label'  => 'Nombre',
            'rules'  => 'required|min_length[3]|max_length[100]|is_unique[skills.name,id,{id}]'
        ],
        'percentage' => [
            'label'  => 'Porcentaje',
            'rules'  => 'required|is_natural_no_zero|less_than_equal_to[100]'
        ]
    ];
    public $studyStore = [
        'entity' => [
            'label'  => 'Entidad',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'title_es' => [
            'label'  => 'Título (ES)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'title_en' => [
            'label'  => 'Título (EN)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'start' => [
            'label'  => 'Fecha de Inicio',
            'rules'  => 'required|valid_date'
        ],
        'end' => [
            'label'  => 'Fecha de Fin',
            'rules'  => 'permit_empty|valid_date'
        ],
        'description_es' => [
            'label'  => 'Descripción (ES)',
            'rules'  => 'permit_empty|min_length[3]'
        ],
        'description_en' => [
            'label'  => 'Description (EN)',
            'rules'  => 'permit_empty|min_length[3]'
        ],
    ];
    public $studyUpdate = [
        'id' => [
            'label'  => 'ID',
            'rules'  => 'required|is_natural_no_zero'
        ],
        'entity' => [
            'label'  => 'Entidad',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'title_es' => [
            'label'  => 'Título (ES)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'title_en' => [
            'label'  => 'Título (EN)',
            'rules'  => 'required|min_length[3]|max_length[120]'
        ],
        'start' => [
            'label'  => 'Fecha de Inicio',
            'rules'  => 'required|valid_date'
        ],
        'end' => [
            'label'  => 'Fecha de Fin',
            'rules'  => 'permit_empty|valid_date'
        ],
        'description_es' => [
            'label'  => 'Descripción (ES)',
            'rules'  => 'permit_empty|min_length[3]'
        ],
        'description_en' => [
            'label'  => 'Description (EN)',
            'rules'  => 'permit_empty|min_length[3]'
        ],
    ];
}
