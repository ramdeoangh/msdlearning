# Release Notes - User Fields Update

**Release Date:** 2024  
**Branch:** `user-update` → Merged to `CI`  
**Version:** v1.0.0

---

## 📋 Overview

This release adds mandatory user registration fields to enhance user profile management and data collection. The update includes new database fields, improved registration forms with a better user experience, and several bug fixes.

---

## ✨ New Features

### 1. Additional User Profile Fields
Added **6 new mandatory fields** to the user registration and profile system:

- **Location** (Dropdown)
  - Options: PHC, Sub-PHC
  - Required field
  
- **Designation** (Dropdown)
  - Options: ANM, MPW
  - Required field
  
- **Phone Number** (Text Input)
  - 10-digit validation
  - Pattern: `[0-9]{10}`
  - Required field
  - Uses existing `phone` column in database
  
- **City** (Text Input)
  - Required field
  
- **State** (Dropdown)
  - All 28 Indian states + 8 union territories (36 total)
  - Alphabetically sorted
  - Required field
  
- **Pincode** (Text Input)
  - 6-digit validation
  - Pattern: `[0-9]{6}`
  - Required field

### 2. Improved Registration Form Layout
- **2-Column Responsive Layout**: Form fields are now arranged side-by-side to reduce scrolling
- Better use of horizontal space
- Mobile-responsive (automatically stacks on smaller screens)

### 3. Indian States Helper
- New helper function: `get_indian_states()`
- Contains all 28 states and 8 union territories of India
- Updated to reflect current administrative divisions (Jammu & Kashmir as UT)

---

## 🐛 Bug Fixes

### 1. Fixed Payment Keys Array Warnings
- Added proper validation for empty `payment_keys` arrays
- Prevents PHP warnings when accessing array keys
- Safe handling of null/empty JSON data

### 2. Fixed Duplicate Phone Field
- Removed duplicate phone input field in admin user edit form
- Single, properly validated phone field remains

### 3. Fixed Social Links Array Handling
- Added validation for `social_links` JSON data
- Prevents warnings when social links data is missing

---

## 📁 Files Changed

### New Files Created:
1. `application/helpers/indian_states_helper.php` - Indian states/UTs helper
2. `application/migrations/add_user_fields.sql` - Database migration script
3. `application/migrations/drop_phone_number_column.sql` - Drop script (if needed)
4. `application/migrations/migrate_phone_number_to_phone.sql` - Data migration (optional)

### Modified Files:
1. `application/config/autoload.php` - Added Indian states helper to autoload
2. `application/controllers/Login.php` - Added field validation and collection
3. `application/models/User_model.php` - Updated add_user() and edit_user() functions
4. `application/models/Api_model.php` - Added fields for API registration
5. `application/views/frontend/default-new/sign_up.php` - New fields + 2-column layout
6. `application/views/backend/admin/user_add.php` - Added new fields
7. `application/views/backend/admin/user_edit.php` - Added new fields + bug fixes
8. `uploads/install.sql` - Updated table structure for new installations

---

## 🗄️ Database Changes

### New Columns Added to `users` Table:
```sql
- location VARCHAR(50)
- designation VARCHAR(50)
- city VARCHAR(100)
- state VARCHAR(100)
- pincode VARCHAR(10)
```

### Note:
- Uses existing `phone` column (no new phone_number column needed)
- All new fields are nullable for backward compatibility
- Migration script available in `application/migrations/add_user_fields.sql`

---

## 📝 Migration Instructions

### For Existing Installations:

1. **Run the migration script:**
   ```sql
   source application/migrations/add_user_fields.sql
   ```
   
   Or execute directly:
   ```sql
   ALTER TABLE `users` 
   ADD COLUMN `location` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `address`,
   ADD COLUMN `designation` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `location`,
   ADD COLUMN `city` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `phone`,
   ADD COLUMN `state` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `city`,
   ADD COLUMN `pincode` VARCHAR(10) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `state`;
   ```

2. **If you previously created `phone_number` column:**
   ```sql
   -- Optional: Migrate data if needed
   source application/migrations/migrate_phone_number_to_phone.sql
   
   -- Then drop the column
   source application/migrations/drop_phone_number_column.sql
   ```

3. **Clear application cache** (if applicable)

---

## 🔄 Backward Compatibility

- Existing users will have `NULL` values for new fields
- Registration form validation ensures new users must fill all mandatory fields
- Admin can update existing users with new field values
- API endpoints updated to handle new fields

---

## 🧪 Testing Checklist

- [ ] User registration form displays all new fields correctly
- [ ] 2-column layout works on desktop and mobile
- [ ] Form validation prevents submission without required fields
- [ ] Phone number accepts only 10 digits
- [ ] Pincode accepts only 6 digits
- [ ] State dropdown shows all 36 Indian states/UTs
- [ ] Location and Designation dropdowns work correctly
- [ ] Admin can add new users with all fields
- [ ] Admin can edit existing users
- [ ] API registration accepts new fields
- [ ] No PHP warnings in error logs
- [ ] Payment keys warnings resolved

---

## 📚 Technical Details

### Validation Rules:
- **Phone Number**: Must be exactly 10 digits (`/^[0-9]{10}$/`)
- **Pincode**: Must be exactly 6 digits (`/^[0-9]{6}$/`)
- **Location**: Must be selected (not empty)
- **Designation**: Must be selected (not empty)
- **State**: Must be selected (not empty)
- **City**: Required text field

### Frontend:
- Bootstrap 4 grid system for responsive layout
- HTML5 form validation
- Pattern matching for phone and pincode

### Backend:
- Server-side validation in Login controller
- Model-level data sanitization
- JSON encoding for complex data structures

---

## ⚠️ Breaking Changes

**None** - This is a backward-compatible update. Existing functionality remains intact.

---

## 🚀 Deployment Steps

1. **Backup Database**
   ```bash
   mysqldump -u username -p database_name > backup.sql
   ```

2. **Pull Latest Code**
   ```bash
   git checkout CI
   git pull origin CI
   ```

3. **Run Database Migration**
   - Execute migration SQL script in your database

4. **Clear Cache** (if applicable)
   ```bash
   rm -rf application/cache/*
   ```

5. **Verify Deployment**
   - Test user registration
   - Test admin user management
   - Check error logs for warnings

---

## 📞 Support

For issues or questions regarding this release, please contact the development team.

---

## 📄 Changelog

### Version 1.0.0 (2024)
- ✨ Added 6 new mandatory user fields
- 🎨 Improved registration form with 2-column layout
- 🐛 Fixed payment_keys array warnings
- 🐛 Fixed duplicate phone field issue
- 🔧 Added Indian states helper function
- 📝 Updated database schema
- ✅ Enhanced form validation

---

**End of Release Notes**

