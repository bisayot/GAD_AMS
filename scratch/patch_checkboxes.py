import os

files_to_patch = [
    'frontend/src/views/staff/ADView.vue',
    'frontend/src/views/college/ADView.vue',
    'frontend/src/views/admin/ADView.vue',
    'frontend/src/views/admin/ADReview.vue'
]

target_block = """        { 
          name: 'Meals', 
          value: mealsVal,
          subOptions: [
            { label: 'Breakfast', checked: Number(d.breakfast_selected) === 1 },
            { label: 'Lunch', checked: Number(d.lunch_selected) === 1 },
            { label: 'Dinner', checked: Number(d.dinner_selected) === 1 }
          ]
        },
        { 
          name: 'Snacks', 
          value: snacksVal,
          subOptions: [
            { label: 'AM Snack', checked: Number(d.am_snack_selected) === 1 },
            { label: 'PM Snack', checked: Number(d.pm_snack_selected) === 1 }
          ]
        }"""

replacement_block = """        { 
          name: 'Meals', 
          value: mealsVal
        },
        { 
          name: 'Snacks', 
          value: snacksVal
        }"""

for filepath in files_to_patch:
    if not os.path.exists(filepath):
        print(f"Not found: {filepath}")
        continue
        
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    if target_block in content:
        content = content.replace(target_block, replacement_block)
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Patched {filepath}")
    else:
        print(f"Target block not found in {filepath}")
