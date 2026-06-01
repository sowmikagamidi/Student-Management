# Student Fee System - Setup & Testing Guide

## Quick Setup Checklist

- [ ] Run database migration
- [ ] Verify all tables created
- [ ] Create fee structures in admin panel
- [ ] Test fee assignment
- [ ] Record test payment

---

## Step 1: Database Migration

### Run the Migration Script
```
URL: http://localhost/school_management/api/migrate_fee_system.php
```

### Expected Output
```json
{
    "success": true,
    "created_tables": [
        "tx_fee_groups (already exists)",
        "tx_student_fee_terms",
        "tx_student_fee_details",
        "tx_student_fee_payments"
    ],
    "failed": []
}
```

### If You See Errors
- Check database name in script
- Verify MySQL is running
- Check user permissions

---

## Step 2: Verify System Setup

### Check System Status
```
URL: http://localhost/school_management/api/check_fee_system.php
```

### Expected Output
```json
{
    "success": true,
    "checks": {
        "database": true,
        "tables": {
            "tx_school_fee_structure": true,
            "tx_fee_groups": true,
            "tx_student_fee_terms": true,
            "tx_student_fee_details": true,
            "tx_student_fee_payments": true
        },
        "files": {
            "admin/student_fee_details.php": true,
            "api/fee_structure_grouped.php": true,
            "api/student_fee_assign_terms.php": true,
            "api/student_term_payment.php": true
        }
    },
    "sample_data": {
        "schools": 2,
        "fee_structures": 8,
        "fee_groups": 3,
        "students_with_fees": 0
    }
}
```

### Troubleshooting
| Issue | Solution |
|-------|----------|
| Database: false | Check MySQL credentials in config |
| Table: false | Run migrate script again |
| File: false | Verify file was created in correct directory |

---

## Step 3: Create Fee Structures

Before assigning fees, you need to set up fee structures for your schools.

### Access Fee Structure Admin
```
URL: http://localhost/school_management/admin/school_fee_structure.php
```

### Create Fee Groups (Optional but Recommended)
1. Click "Add Fee Group"
2. Enter Group Name: "Academic Fees"
3. Save

### Create Fee Structure
1. Select School
2. Enter Academic Year
3. Add Fees:
   - Tuition Fee: ₹5000
   - Books Fee: ₹1000
   - Lab Fee: ₹500
4. Save

---

## Step 4: Test Fee Assignment

### Access Student Fee Details
```
URL: http://localhost/school_management/admin/student_fee_details.php
```

### Test Workflow
1. Click **"+ Assign Fees"** button
2. Select your test school
3. Select Academic Year 2024
4. Select Class
5. Create Terms and assign fees (see table below)
6. Click **"💰 Assign Fees to Students"**

### Test Case: 3-Term Structure

**Create Term 1:**
- Add Fee: Tuition Fee, Amount: 1500
- Add Fee: Books Fee, Amount: 300
- Term 1 Total: 1800

**Create Term 2:**
- Add Fee: Tuition Fee, Amount: 1500
- Add Fee: Books Fee, Amount: 300
- Add Fee: Lab Fee, Amount: 200
- Term 2 Total: 2000

**Create Term 3:**
- Add Fee: Tuition Fee, Amount: 2000
- Add Fee: Books Fee, Amount: 400
- Add Fee: Lab Fee, Amount: 300
- Term 3 Total: 2700

**Verify:**
- Remaining amount shows ₹0
- Click submit
- Success message appears

---

## Step 5: View Assigned Fees

### Apply Filters
1. Select School (your test school)
2. Select Academic Year (2024)
3. Select Class
4. Click **"Apply Filter"**

### Expected Display
- Student name card appears
- Term 1 card with fees breakdown
- Term 2 card with fees breakdown
- Term 3 card with fees breakdown

### Sample View
```
Student: John Doe
├─ Term 1 (Total: ₹1800)
│  ├─ Tuition Fee: ₹1500
│  └─ Books Fee: ₹300
├─ Term 2 (Total: ₹2000)
│  ├─ Tuition Fee: ₹1500
│  ├─ Books Fee: ₹300
│  └─ Lab Fee: ₹200
└─ Term 3 (Total: ₹2700)
   ├─ Tuition Fee: ₹2000
   ├─ Books Fee: ₹400
   └─ Lab Fee: ₹300
```

---

## Step 6: Record Test Payment

### Click "Pay" Button
1. In Term 1 footer, click **"Pay ₹1800"**
2. Enter Payment Details:
   - Amount: 1800
   - Method: Cash
   - Transaction ID: TEST001
3. Click **"Record Payment"**
4. Success message appears

### Verify Payment
1. Refresh the page
2. Term 1 status should show:
   - Paid: ₹1800
   - Pending: ₹0

---

## Common Testing Scenarios

### Scenario 1: Partial Payment
```
Term Total: ₹2000
Payment 1: ₹500 (via Cash)
Payment 2: ₹1000 (via UPI)
Payment 3: ₹500 (via Card)
Status: PAID ✓
```

### Scenario 2: Over-Allocation Prevention
```
School Total: ₹6000
Term 1: Tuition ₹2000 (Remaining: ₹4000)
Term 2: Tuition ₹2000 (Remaining: ₹2000)
Term 3: Tuition ₹3000 ← ERROR! Exceeds remaining
Fix: Reduce to ₹2000
```

### Scenario 3: Multiple Students
```
Class 5-A (5 students)
├─ Student 1: Assigned ✓
├─ Student 2: Assigned ✓
├─ Student 3: Assigned ✓
├─ Student 4: Assigned ✓
└─ Student 5: Assigned ✓
```

---

## Database Verification

### Check Created Assignments
```sql
SELECT COUNT(*) as total_assignments 
FROM tx_student_fee_terms 
WHERE academic_year = 2024;
```

### Check Payments Recorded
```sql
SELECT student_id, COUNT(*) as payment_count, SUM(amount) as total_paid
FROM tx_student_fee_payments
WHERE payment_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY student_id;
```

### Verify Fee Distribution
```sql
SELECT 
    sft.term_name,
    COUNT(DISTINCT sft.student_id) as students,
    SUM(sft.term_amount) as total_allocated
FROM tx_student_fee_terms sft
WHERE sft.academic_year = 2024
GROUP BY sft.term_name;
```

---

## Error Troubleshooting

### Error 1: "Error loading fee groups"
**Cause**: API returning invalid JSON (likely PHP error)
**Fix**: 
```
1. Check api/fee_structure_grouped.php for syntax errors
2. Verify school_id exists in database
3. Check if fee_structure_grouped.php has display_errors = 0
```

### Error 2: "Total allocated does not match"
**Cause**: Sum of all fees doesn't equal school total
**Fix**:
```
1. Check remaining amount box
2. Ensure it shows ₹0 before submitting
3. Verify each fee allocation
```

### Error 3: "No students found"
**Cause**: Selected class has no active students
**Fix**:
```
1. Verify students exist in selected class
2. Check student status is 'A' (Active)
3. Ensure students have user_role = 'student'
```

### Error 4: Dropdown shows nothing
**Cause**: No fee structures created for school
**Fix**:
```
1. Go to school_fee_structure.php
2. Create fee structures
3. Retry assignment
```

---

## Performance Testing

### Load Test: Assign to Large Class
```
Test: Assign fees to 100 students in Class 5
Metric: Should complete in < 5 seconds
```

### Data Verification
```sql
-- After assignment test
SELECT COUNT(DISTINCT student_id) as total_students
FROM tx_student_fee_details
WHERE academic_year = 2024;
```

---

## Browser DevTools Testing

### Check Network Calls
Press F12 → Network tab
```
When you click "Assign Fees to Students":
1. student_fee_assign_terms.php - Status 200
2. Response should be: {"success": true, "assigned_count": X}
```

### Check Console
```
Should have NO errors
Should see: "Success toast message"
```

---

## Rollback if Needed

### Delete Test Data
```sql
-- Remove test fees
DELETE FROM tx_student_fee_payments 
WHERE payment_date >= '2024-01-01' AND payment_date <= NOW();

DELETE FROM tx_student_fee_details 
WHERE academic_year = 2024;

DELETE FROM tx_student_fee_terms 
WHERE academic_year = 2024;
```

---

## Final Verification Checklist

- [ ] Database tables created
- [ ] Fee structures exist
- [ ] Can assign fees to class
- [ ] Fees appear in student list
- [ ] Can record payments
- [ ] Remaining calculations accurate
- [ ] Error messages display correctly
- [ ] Data persists after refresh
- [ ] API responses valid JSON
- [ ] No console errors

---

## Next Steps After Testing

1. **Production Setup**: Create actual fee structures for all schools
2. **User Training**: Show admin how to use system
3. **Schedule Setup**: Create fee structures for upcoming academic years
4. **Report Setup**: Create payment reports and statements
5. **Backup Plan**: Regular database backups

---

**Testing Complete!** ✓ System is ready for production use.
