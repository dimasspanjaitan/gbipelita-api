<!-- TYPE
uuid = char:36
string = varchar:225
-->

- User: users
        - id: uuid|primary|required
        - username: string|required
        - first_name: string|nullable
        - last_name: string|nullable
        - nickname: string|required
        - email: string|nullable
        - password: string|required
        - status: enum|default:active|index|required  // ['active', 'inactive']
        - photo: string|nullable
        - created_at: timestamp|nullable
        - updated_at: timestamp|nullable
        - deleted_at: timestamp|nullable

- UserAvailability: // migration tablenya belum dibuat
        - id: uuid|primary|required
        - user_id: uuid|index|required
        - availability: enum|default:available  // ['available', 'training', 'suspended', 'on_leave']
        - start_date: date|nullable
        - end_date: date|nullable

- Department: departments
        - id: uuid|primary|required
        - name: string|unique|required
        - alias: string|unique|nullable
        - status: enum|default:active|index|required  // ['active', 'inactive']
        - content: text|nullable
        - created_at: timestamp|nullable
        - updated_at: timestamp|nullable
        - deleted_at: timestamp|nullable

- UserDepartment: use_department
        - user_id: uuid|index|primary|required
        - department_id: uuid|index|primary|required

- Division: divisions
        - id: uuid|primary|required
        - name: string|unique|required
        - alias: string|unique|nullable
        - department_id: uuid|index|required
        - status: enum|default:active|index|required  // ['active', 'inactive']
        - content: text|nullable
        - created_at: timestamp|nullable
        - updated_at: timestamp|nullable
        - deleted_at: timestamp|nullable

- UserDivision: user_division
        - user_id: uuid|index|primary|required
        - division_id: uuid|index|primary|required
        - priority: integer|default:1|required

- Master: // migration tablenya belum dibuat
        - id: uuid|primary|required
        - type: string|required|index
        - name: string|required
        - start_time: time|nullable
        - end_time: time|nullable
        - status: string|default:active|required
        - order: integer|default:1|required
        - data: json|nullable

- ScheduleAssignments: // migration tablenya belum dibuat
        - id: uuid|primary|required
        - user_id: uuid|index|required
        - date: date|required
        - session_id: string|index|required // foreign key ke Master
        - division_id: string|index|required
        - role_in_session: string
        - content: text|nullable
        - assigned_by: string|index|required // foreign key ke User

- UserScheduleAvailability: // migration tablenya belum dibuat
        - id: uuid|primary|required
        - user_id: uuid|index|required
        - date: date|required
        - session_id: uuid|index|required // foreign key ke Master

- Module: modules
        - id: uuid|primary|required
        - name: string|unique
        - slug: string|unique
        - icon: string|nullable
        - order: integer|default:0
        - description: string|nullable
        - created_at: timestamp|nullable
        - updated_at: timestamp|nullable
        - deleted_at: timestamp|nullable

- Action: actions
        - id: uuid|primary|required
        - module_id: uuid|index|required
        - is_default_action: boolean|default:false
        - name: string|unique|required
        - label: string|unique|required
        - permission_name: string|unique|required
        - order: integer|default:0
        - created_at: timestamp|nullable
        - updated_at: timestamp|nullable
        - deleted_at: timestamp|nullable

- Module Action: module_action
        - module_id: uuid|required
        - action_id: uuid|required

- Role: roles
        - id: uuid|primary|required
        - name: string|required
        - gurad_name: string|required
        - created_at: timestamp|nullable
        - updated_at: timestamp|nullable
        - deleted_at: timestamp|nullable

- Permission: permissions
        - id: uuid|primary|required
        - name: string|required
        - quard_name: string|required
        - created_at: timestamp|nullable
        - updated_at: timestamp|nullable
        - deleted_at: timestamp|nullable

- Role Has Permission: role_has_permissions
        - role_id: uuid|primary|required
        - permission_id: uuid|primary|required

- Model Has Role: model_has_permissions
        - role_id: uuid|primary|required
        - model_id: uuid|primary|required
        - model_type: string|primary|required

--------------------------------------------------------
- Event
        - id: uuid|primary
        - name: string|required
        - priority: integer|default:1|required
        - start_date: date|required
        - end_date: date|required
        - category_id: string|index|required
        - content: text|required
        - data: json|nullable
        - start_time: time|required
        - end_time: time|required
        - organizer: string|nullable
        - location: string|nullable
        - lat_long: string|nullable
        - photo: json|nullable
        - banner: string|nullable
