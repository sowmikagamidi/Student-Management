# Student Fee Details System - Complete Guide

## Overview
The enhanced student fee details system allows administrators to assign fees to students on a term-by-term basis with flexible fee allocation. Each fee can be split across multiple terms with custom amounts.

## Key Features

### 1. Create Term Button
- Click **"+ Create Term"** to add new terms (Term 1, Term 2, etc.)
- Each term is created with:
  - Term name field (customizable)
  - Fee dropdown showing available fees
  - Amount input field
  - Add button to add fees to the term
  - Total amount display for that term

### 2. Fee Allocation Per Term
- **Select a Fee**: Choose from dropdown of available fees
  - Shows remaining amount available for each fee
  - Shows group name the fee belongs to
  - Disabled fees that are fully allocated
  
- **Enter Amount**: Specify how much of that fee to allocate in this term
  - Cannot exceed the remaining amount for that fee
  - Validation prevents over-allocation

- **Add Fee**: Click to add the fee to the current term
  - Fee appears in a card below with the allocated amount
  - Delete (✕) button removes the fee from the term

### 3. Remaining Amount Tracking
- Displays total remaining to allocate across all fees
- Shows green checkmark when all fees are fully allocated
- Updates in real-time as fees are added/removed

### 4. Term Management
- **Edit Term Name**: Click the term name input to customize it
- **View Term Total**: Shows total amount allocated to the term
- **Remove Term**: Delete button removes entire term (auto-renumbers remaining terms)

## Workflow

### Step 1: Select School
1. Click **"+ Assign Fees"** button
2. Select a School
3. System loads:
   - Total school fee amount
   - Fee groups and individual fees
   - Available classes

### Step 2: Fill Basic Details
- Select Academic Year
- Select Class
- (Optional) Enter Section

### Step 3: Create Terms and Allocate Fees
1. Click **"+ Create Term"** to create Term 1
2. Select a fee from dropdown
3. Enter the amount for this fee in this term
4. Click **"+ Add Fee"** button
5. Fee appears below with delete option
6. Repeat for more fees in same term
7. Click **"+ Create Term"** again for Term 2
8. Allocate remaining fees across terms
9. Continue until all fees are fully allocated

### Step 4: Submit Assignment
- Verify remaining amount shows ₹0
- Click **"💰 Assign Fees to Students"** button
- Fees are assigned to all students in selected class/section

## Database Structure

### Required Tables
```sql
-- Fee Structure (existing)
CREATE TABLE tx_school_fee_structure (
    id INT PRIMARY KEY,
    school_id INT,
    group_id INT,
    fee_name VARCHAR(255),
    amount DECIMAL(10,2),
    academic_year INT,
    created_at TIMESTAMP,
    is_deleted INT DEFAULT 0
);

-- Fee Groups (existing)
CREATE TABLE tx_fee_groups (
    id INT PRIMARY KEY,
    school_id INT,
    group_name VARCHAR(255),
    created_at TIMESTAMP
);

-- Student Fee Terms (required)
CREATE TABLE tx_student_fee_terms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    school_id INT NOT NULL,
    term_name VARCHAR(100),
    term_amount DECIMAL(10,2),
    academic_year INT,
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES tx_users(user_id),
    FOREIGN KEY (school_id) REFERENCES tx_school(school_id)
);

-- Student Fee Details (required)
CREATE TABLE tx_student_fee_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    school_id INT NOT NULL,
    term_id INT NOT NULL,
    group_id INT,
    fee_name VARCHAR(255),
    amount DECIMAL(10,2),
    academic_year INT,
    due_date DATE,
    paid_amount DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES tx_users(user_id),
    FOREIGN KEY (school_id) REFERENCES tx_school(school_id),
    FOREIGN KEY (term_id) REFERENCES tx_student_fee_terms(id)
);
```

## API Endpoints

### 1. Get School Fee Structure (Grouped)
**Endpoint**: `api/fee_structure_grouped.php`
**Method**: GET
**Parameters**: `school_id`
**Returns**: Fees grouped by group_id with total amount

**Example Response**:
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
                },
                {
                    "fee_name": "Books Fee",
                    "amount": 1000
                }
            ]
        }
    ],
    "total_amount": 6000
}
```

### 2. Assign Fees to Students (By Terms)
**Endpoint**: `api/student_fee_assign_terms.php`
**Method**: POST
**Payload**:
```json
{
    "school_id": 1,
    "academic_year": 2024,
    "class_id": 5,
    "section": "A",
    "total_amount": 6000,
    "terms": [
        {
            "term_name": "Term 1",
            "amount": 2000,
            "fees": [
                {
                    "group_id": 1,
                    "fee_name": "Tuition Fee",
                    "amount": 1500
                },
                {
                    "group_id": 1,
                    "fee_name": "Books Fee",
                    "amount": 500
                }
            ]
        },
        {
            "term_name": "Term 2",
            "amount": 2000,
            "fees": [
                {
                    "group_id": 1,
                    "fee_name": "Tuition Fee",
                    "amount": 1500
                },
                {
                    "group_id": 1,
                    "fee_name": "Books Fee",
                    "amount": 500
                }
            ]
        },
        {
            "term_name": "Term 3",
            "amount": 2000,
            "fees": [
                {
                    "group_id": 1,
                    "fee_name": "Tuition Fee",
                    "amount": 2000
                }
            ]
        }
    ]
}
```

### 3. Get Student Fee List (Grouped)
**Endpoint**: `api/student_fee_list_grouped.php`
**Method**: GET
**Parameters**: `school_id`, `academic_year`, `class_id`, `student_id`, `page`, `limit`
**Returns**: Student fees grouped by term and group

## UI Components

### Term Card
- Header with term name and total badge
- Fee selection dropdown with remaining amounts
- Amount input field
- Add Fee button
- List of added fees with delete buttons

### Fee Item
- Displays fee name
- Shows group name
- Shows allocated amount
- Delete button (red X circle)

### Remaining Amount Box
- Shows total remaining to allocate
- Changes color to green when complete
- Updates in real-time

## Frontend Functions

### Core Functions
- `addTerm()` - Creates new term
- `removeTerm(index)` - Removes term by index
- `addFeeToTerm(termIndex)` - Adds fee to specific term
- `removeFeeFromTerm(termIndex, feeIndex)` - Removes fee from term
- `getAvailableFees()` - Returns all fees for current school
- `getAllocatedAmount(feeName)` - Total allocated for a fee
- `getRemainingFeeAmount(feeName)` - Remaining amount for a fee
- `getTotalAllocatedAmount()` - Total of all allocations
- `renderTermsList()` - Renders all terms UI
- `updateRemainingDisplay()` - Updates remaining amount display

## Validation Rules

1. **Fee Selection**: Must select a fee from dropdown
2. **Amount Entry**: Must be positive number
3. **Amount Limit**: Cannot exceed remaining amount for that fee
4. **Duplicate Fees**: Same fee cannot be added twice to same term
5. **Term Validation**: Each term must have at least one fee
6. **Total Allocation**: Total allocated must equal school total fee
7. **Class Selection**: Must select a class before assigning

## Error Messages

| Error | Message |
|-------|---------|
| No fee selected | "Please select a fee" |
| Invalid amount | "Please enter a valid amount" |
| Amount too high | "Amount cannot exceed remaining amount of ₹X for this fee" |
| Duplicate fee | "This fee is already added to this term" |
| Missing term fees | "Term X has no fees assigned" |
| Total mismatch | "Total allocated does not match school total" |

## Features Breakdown

### 1. Real-time Remaining Calculation
```javascript
// Tracks per-fee allocation across all terms
// Updates UI with accurate remaining amounts
// Prevents over-allocation
```

### 2. Flexible Fee Distribution
```javascript
// Same fee can be split across multiple terms
// Each term can have different fees
// Custom amounts per fee per term
```

### 3. Term Management
```javascript
// Add unlimited terms
// Auto-renaming when terms are removed
// Editable term names
// Individual term totals
```

### 4. Visual Feedback
```javascript
// Green badge when fully allocated
// Color-coded remaining box
// Disabled options for fully allocated fees
// Success/error toasts
```

## Usage Tips

1. **Best Practice**: Organize fees logically across terms (e.g., Monthly payments)
2. **Validation**: System validates before submission
3. **Edit Later**: Currently not possible - create new assignment
4. **Bulk Assignment**: Assigns to entire class at once
5. **Academic Year**: Can assign for multiple years

## Troubleshooting

### Issue: "Total allocated does not match school total"
- **Solution**: Check remaining amount box, ensure it shows ₹0

### Issue: Fee dropdown shows "Fully Allocated"
- **Solution**: Fee is already split across all terms, cannot allocate more

### Issue: Cannot add fee to term
- **Solution**: Check if fee is already in term or exceeds remaining amount

### Issue: Terms not showing
- **Solution**: Click "Create Term" button first

## Support

For issues or questions, contact system administrator.
