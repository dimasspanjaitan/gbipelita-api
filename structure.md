- User:
        - id: uuid|primary
        - name: string|required
        - username: string|required
        - email: string|nullable
        - password: string|required
        - status: string|default:active|index|required
        - photo: string|nullable

- UserAvailability
        - id: uuid|primary
        - user_id: string|index|required
        - availability: string|default:available  // ['available', 'training', 'suspended', 'on_leave']
        - start_date: date|nullable
        - end_date: date|nullable

- Department
        - id: uuid|primary
        - name: string|unique|required
        - content: text|nullable

- UserDepartment
        - user_id: string|index|required|primary
        - department_id: string|index|required|primary

- Division
        - id: uuid|primary
        - name: string|unique|required
        - department_id: string|index|required
        - content: text|nullable

- UserDivision
        - user_id: string|index|required|primary
        - division_id: string|index|required|primary
        - priority: integer|default:1|required

- Master
        - id: uuid|primary
        - type: string|required|index
        - name: string|required
        - start_time: time|nullable
        - end_time: time|nullable
        - status: string|default:active|required
        - order: integer|default:1|required
        - data: json|nullable

- ScheduleAssignments
        - id: uuid|primary
        - user_id: string|index|required
        - date: date|required
        - session_id: string|index|required // foreign key ke Master
        - division_id: string|index|required
        - role_in_session: string
        - content: text|nullable
        - assigned_by: string|index|required // foreign key ke User

- UserScheduleAvailability  // unique (user_id, date, session_id)
        - id: uuid|primary
        - user_id: string|index|required
        - date: date|required
        - session_id: string|index|required // foreign key ke Master

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