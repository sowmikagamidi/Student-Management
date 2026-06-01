# Quick Implementation Guide - Student Fee Details System

## Setup Steps

### 1. Database Initialization
**Run the migration script to create required tables:**
```
Visit: http://localhost/school_management/api/migrate_fee_system.php
```

Expected output:
```json
{
    "success": true,
    "created_tables": [
        "tx_fee_groups (already exists)",
        "tx_student_fee_terms",
        "tx_student_fee_details",
        "tx_student_fee_payments"
    ]
}
```

### 2. Access the System
```
URL: http://localhost/school_management/admin/student_fee_details.php
```

---

## User Workflow

### Scenario: Assign fees to Class 5 for Academic Year 2024

#### Step 1: Start Assignment
1. Click **"+ Assign Fees"** button
2. Select School: "ABC School"
3. Select Academic Year: "2024"

**What you'll see:**
- Total fee amount appears (e.g., ₹6000)
- Fee groups display with breakdown

#### Step 2: Select Class
1. Select Class: "5"
2. (Optional) Select Section: "A"

#### Step 3: Create Terms and Add Fees

**Create Term 1:**
1. Click **"+ Create Term"**
2. Term 1 card appears

**Add First Fee to Term 1:**
1. Click dropdown: "-- Select Fee --"
2. Select: "Tuition Fee - Academic Fees (Remaining: ₹5000)"
3. Enter Amount: "1500"
4. Click **"+ Add Fee"**
5. "Tuition Fee ₹1500" appears below

**Add Second Fee to Term 1:**
1. Click dropdown again
2. Select: "Books Fee - Academic Fees (Remaining: ₹1000)"
3. Enter Amount: "500"
4. Click **"+ Add Fee"**
5. Term 1 now shows Total: ₹2000

**Create Term 2:**
1. Click **"+ Create Term"** again
2. Term 2 card appears

**Add Fees to Term 2:**
1. Add "Tuition Fee ₹1500"
2. Add "Books Fee ₹500"
3. Term 2 Total: ₹2000

**Create Term 3:**
1. Click **"+ Create Term"**
2. Add "Tuition Fee ₹2000"
3. Term 3 Total: ₹2000

**Check Remaining:**
- Remaining box should show: ₹0 (green background)

#### Step 4: Submit
1. Click **"💰 Assign Fees to Students"**
2. System processes and assigns to all students in Class 5
3. Success message appears

---

## Feature Reference

### Adding Fees to a Term

| Step | Action | Details |
|------|--------|---------|
| 1 | Click dropdown | Shows available fees with remaining amounts |
| 2 | Select fee | Fee name shows with group info |
| 3 | Enter amount | Amount can't exceed remaining |
| 4 | Click "+ Add Fee" | Fee card appears below |
| 5 | View in term | Fee displays with amount and delete button |

### Modifying Fees

| Action | How | Result |
|--------|-----|--------|
| Delete fee from term | Click red ✕ button | Fee removed, term total updates |
| Edit fee amount | Must remove and re-add | Allows changing allocation |
| Add same fee twice | Not allowed | Error: "Fee already added" |

### Managing Terms

| Action | How | Result |
|--------|-----|--------|
| Rename term | Click term name input | Edit name directly |
| Remove term | Click red "🗑️ Remove" | Term deleted, others auto-renumbered |
| View term total | Look at badge | Shows sum of all fees in term |

---

## Real-Time Calculations

### Remaining Amount Formula
```
Remaining = Total School Fee - Sum of All Allocations

Example:
School Total: ₹6000
Term 1 Tuition: ₹1500 + Term 1 Books: ₹500 = ₹2000
Term 2 Tuition: ₹1500 + Term 2 Books: ₹500 = ₹2000
Term 3 Tuition: ₹2000 = ₹2000
Total Allocated: ₹6000
Remaining: ₹0 ✅
```

### Per-Fee Allocation
```
Tuition Fee Total: ₹5000
- Allocated in Term 1: ₹1500
- Allocated in Term 2: ₹1500
- Allocated in Term 3: ₹2000
Remaining: ₹0

Books Fee Total: ₹1000
- Allocated in Term 1: ₹500
- Allocated in Term 2: ₹500
Remaining: ₹0
```

---

## Validation Checks

### Before Submission
```
✓ School selected
✓ Academic year selected
✓ Class selected
✓ At least one term created
✓ Each term has at least one fee
✓ Total allocated = School total (within ₹0.01)
✓ No duplicate fees in same term
✓ All amounts are positive
```

### During Fee Addition
```
✓ Fee selected (not empty)
✓ Amount is valid positive number
✓ Amount ≤ remaining for that fee
✓ Fee not already in this term
```

---

## Example: Multi-Quarter Fee Structure

**School:** St. Xavier's
**Total Fee:** ₹12,000
**Academic Year:** 2024
**Class:** 10-A

**Fee Breakdown:**
- Tuition: ₹8000
- Books: ₹2000
- Laboratory: ₹1500
- Activity: ₹500

**Term Structure:**

| Term | Tuition | Books | Lab | Activity | Total |
|------|---------|-------|-----|----------|-------|
| Q1 | ₹2000 | ₹500 | ₹400 | ₹150 | ₹3050 |
| Q2 | ₹2000 | ₹500 | ₹400 | ₹150 | ₹3050 |
| Q3 | ₹2000 | ₹500 | ₹400 | ₹100 | ₹3000 |
| Q4 | ₹2000 | ₹500 | ₹300 | ₹100 | ₹2900 |
| **Total** | **₹8000** | **₹2000** | **₹1500** | **₹500** | **₹12,000** |

---

## API Integration

### Your Application Makes These API Calls:

1. **Get Fee Structure**
   ```
   GET /api/fee_structure_grouped.php?school_id=1
   ```

2. **Get Class List**
   ```
   GET /api/classes_list.php?school_id=1
   ```

3. **Assign Fees**
   ```
   POST /api/student_fee_assign_terms.php
   Body: JSON with school_id, academic_year, class_id, terms array
   ```

4. **Get Student Fees**
   ```
   GET /api/student_fee_list_grouped.php?school_id=1&academic_year=2024
   ```

5. **Record Payment**
   ```
   POST /api/student_term_payment.php
   Body: JSON with student_id, term_id, amount
   ```

---

## Troubleshooting

### Q: Dropdown showing "Fully Allocated"
**A:** That fee has been completely distributed across terms. Cannot add more.

### Q: Error "Total allocated does not match"
**A:** Remaining amount box doesn't show ₹0. Verify allocations sum to school total.

### Q: Cannot add same fee twice
**A:** By design - each fee once per term. Remove and re-add with new amount if needed.

### Q: Term 1, Term 2 disappeared
**A:** They weren't saved. Must complete form and click submit. Refresh doesn't save.

### Q: Fees not showing after adding
**A:** Check browser console for errors (F12). Verify amount is valid.

---

## Support & Maintenance

### Regular Tasks
- **Monthly**: Review fee allocations
- **Quarterly**: Verify student payments against terms
- **Yearly**: Archive completed academic year data

### Database Maintenance
```sql
-- Check unpaid fees
SELECT * FROM tx_student_fee_terms 
WHERE payment_status = 'pending' AND academic_year = 2024;

-- Get payment summary
SELECT SUM(amount) as total_payments FROM tx_student_fee_payments 
WHERE payment_date >= '2024-01-01';

-- Verify data integrity
SELECT COUNT(*) FROM tx_student_fee_details sfd
WHERE sfd.amount != (
    SELECT SUM(amount) FROM tx_student_fee_terms sft 
    WHERE sft.id = sfd.term_id
);
```

---

## Performance Tips

1. **Optimize Query**: Index on (student_id, term_id)
2. **Bulk Operations**: Assign to entire class vs. individual students
3. **Archive Old Data**: Move completed academic years to archive table
4. **Cache**: Fee structures change rarely - can cache in browser

---

**System Version**: 1.0
**Last Updated**: 2024
**Database**: MySQL 5.7+
