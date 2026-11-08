# EWA Trainee Fields

Adds the Join EWA registration fields to Drupal user accounts in one step.

## Install
1. Unzip into `web/modules/custom/` → `web/modules/custom/ewa_trainee_fields/`
2. Enable: `drush en ewa_trainee_fields -y` (or Extend › EWA Trainee Fields › Install)
3. Check: Configuration › People › Account settings › Manage fields (`/admin/config/people/accounts/fields`)

Requires a taxonomy vocabulary with the machine name `programs` for "Selected programs".

## What it creates (user entity)
Personal: field_national_id, field_first_name, field_father_name, field_grandfather_name, field_last_name,
field_gender, field_date_of_birth, field_height, field_weight, field_mobile, field_alt_mobile,
field_alt_relation, field_health_declaration
Academic: field_qualification, field_major, field_graduation_year, field_english_level, field_step_grade,
field_gpa, field_university
Employment: field_employed, field_social_insurance, field_qiwa, field_commercial_reg, field_hrdf,
field_referral, field_data_confirm
Programs: field_programs (taxonomy "programs", unlimited)

List keys match the values the Next.js registration form sends. Arabic labels are added when the site
has Arabic enabled (language module).

- Fields that already exist are skipped, so it is safe to enable again.
- Fields are added to the user form and user page displays; reorder or hide them under
  Manage form display / Manage display.
- Uninstalling the module keeps the fields and their data. Delete fields from Manage fields if needed.
