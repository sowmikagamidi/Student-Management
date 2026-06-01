# Troubleshooting: 500 Error - Fee Structure API

## Problem
When clicking "Assign Fees" and selecting a school, getting:
```
GET http://localhost/school_management/api/fee_structure_grouped.php?school_id=9 500 (Internal Server Error)
```

## Step 1: Run Diagnostics

### Check System Status
```
URL: http://localhost/school_management/api/diagnostic.php
```

### Expected Output
```json
{
    "database": true,
    "tables": {
        "tx_school": { "exists": true },
        "tx_school_fee_structure": { "exists": true },
        "tx_student_fee_terms": { "exists": true },
        "tx_student_fee_details": { "exists": true },
        ...
    },
    "sample_data": {
        "school_9": { "id": 9, "school_name": "ABC School" },
        "fee_structures_school_9": 5
    },
    "errors": []
}
```

## Step 2: Identify the Issue

### Error Type 1: Tables Don't Exist
**Symptom**: `"exists": false` for fee tables
**Fix**:
```
1. Visit: http://localhost/school_management/api/migrate_fee_system.php
2. Run all migrations
3. Refresh diagnostics page to verify
```

### Error Type 2: School Doesn't Have Fees
**Symptom**: `"fee_structures_school_9": 0`
**Fix**:
```
1. Go to admin/school_fee_structure.php
2. Select School ID 9
3. Add some fee structures (e.g., Tuition, Books)
4. Save and return to student fees
```

### Error Type 3: School Doesn't Exist
**Symptom**: `"school_9": null` or empty
**Fix**:
```
1. Note: School ID 9 may not exist
2. Use a school ID from sample_schools list instead
3. Or create a new school in admin
```

### Error Type 4: Database Connection Failed
**Symptom**: `"database": false` with connection error
**Fix**:
```
1. Verify MySQL is running
2. Check database name (should be "school_management")
3. Check credentials (root / no password)
4. Restart Apache and MySQL
```

---

## Step 3: Manual Testing

### Test API Directly
```
URL: http://localhost/school_management/api/fee_structure_grouped.php?school_id=1
```

Expected Response:
```json
{
    "success": true,
    "data": [
        {
            "group_id": 1,
            "group_name": "Academic Fees",
            "fees": [
                {
                    "fee_name": "Tuition Fee",
                    "amount": 5000
                }
            ]
        }
    ],
    "total_amount": 5000
}
```

### Common Response Errors
| Response | Issue | Solution |
|----------|-------|----------|
| 500 with empty body | PHP error | Check PHP logs, run migrate |
| 400 | School ID missing | Provide ?school_id=X |
| 200 but no data | No fees for school | Create fee structures |

---

## Step 4: Check MySQL Directly

### Test Tables Exist
```sql
SHOW TABLES LIKE 'tx_student_fee%';
SHOW TABLES LIKE 'tx_school_fee%';
```

Expected: Should show all 7 required tables

### Check Schools
```sql
SELECT id, school_name, school_code FROM tx_school LIMIT 5;
```

Expected: At least one school listed

### Check Fee Structures
```sql
SELECT id, school_id, group_id, fee_name, amount 
FROM tx_school_fee_structure 
WHERE school_id = 1 
LIMIT 5;
```

Expected: Some rows returned (if school 1 exists)

### Check Students
```sql
SELECT user_id, full_name, school_id, class_id 
FROM tx_users 
WHERE role = 'student' 
LIMIT 5;
```

Expected: At least one student

---

## Step 5: Common Issues & Solutions

### Issue 1: "Unexpected end of JSON input"
**Cause**: API returning empty response
**Solutions**:
1. Check if school_id exists
2. Run migration script
3. Check PHP error logs

### Issue 2: "SyntaxError: Unexpected token '<'"
**Cause**: API returning HTML instead of JSON (PHP error)
**Solutions**:
1. Enable temporary display_errors in API
2. Check database connection
3. Verify tables exist

### Issue 3: "500 Internal Server Error"
**Cause**: PHP error on server
**Solutions**:
1. Check Apache error log: `C:\xampp\apache\logs\error.log`
2. Check PHP error log: `C:\xampp\php\logs\php_error.log`
3. Verify all table names are correct

### Issue 4: Cannot Select School
**Cause**: school_list.php returning empty
**Solutions**:
1. Verify schools exist in database
2. Check school status (not deleted)
3. Verify school_id column exists

---

## Quick Checklist

- [ ] Run diagnostic.php and review errors
- [ ] Verify all tables exist with SHOW TABLES
- [ ] Check school record exists in database
- [ ] Check fee structures exist for your school
- [ ] Verify PHP error logs show no errors
- [ ] Test API endpoint directly with ?school_id=1
- [ ] Clear browser cache (Ctrl+Shift+Delete)
- [ ] Refresh page and try again

---

## Getting More Details

### Enable Temporary Debugging
Edit `/api/fee_structure_grouped.php` temporarily:
```php
// Change this line:
ini_set('display_errors', 0);

// To this:
ini_set('display_errors', 1);
```

Then check API response - it will show the exact error.

**Important**: Change back to 0 when done!

### Check Apache Error Log
```
File: C:\xampp\apache\logs\error.log
```

### Check PHP Error Log
```
File: C:\xampp\php\logs\php_error.log
```

---

## Database Setup Verification

### Run This SQL to Check Setup
```sql
-- Check tables
SHOW TABLES FROM school_management;

-- Check school table structure
DESCRIBE tx_school;

-- Check fee structure table structure
DESCRIBE tx_school_fee_structure;

-- Count data
SELECT 'Schools' as entity, COUNT(*) as count FROM tx_school
UNION
SELECT 'Fee Structures' as entity, COUNT(*) FROM tx_school_fee_structure
UNION
SELECT 'Users', COUNT(*) FROM tx_users
UNION
SELECT 'Students', COUNT(*) FROM tx_users WHERE role = 'student';
```

---

## If Still Having Issues

### Provide These Details for Support
1. Output of diagnostic.php
2. School ID you're trying to use
3. PHP error log contents
4. Apache error log contents
5. Result of SHOW TABLES query
6. Screenshots of the error

### Try These Steps
1. Stop Apache and MySQL
2. Restart both services
3. Clear browser cache
4. Try a different school ID
5. Test with school_id=1 (if it exists)

---

## Prevention

### Always Check Before Using
1. Verify school exists
2. Verify fee structures created
3. Verify students in class
4. Check database connection
5. Review diagnostic page monthly

### Maintenance
- Back up database weekly
- Monitor error logs daily
- Test system monthly
- Update fee structures at start of term

---

## Next Steps After Fixing

Once the diagnostic shows all green:
1. Refresh student_fee_details.php
2. Click "+ Assign Fees" button
3. School dropdown should now work
4. Select school from list
5. Fees should load automatically

---

**Need More Help?**

Check these files for guidance:
- IMPLEMENTATION_GUIDE.md - Setup steps
- TESTING_GUIDE.md - Testing procedures
- QUICK_SETUP.txt - Quick reference
