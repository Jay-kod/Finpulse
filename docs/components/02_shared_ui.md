# Component 02: Shared UI Library

## Overview
This component implements the core Design System using standard HTML elements styled with Tailwind CSS v4 and enhanced with Alpine.js where necessary (e.g., modals, dismissible alerts). All components are designed to be highly reusable and accessible.

## Available Components

### Forms
- `<x-ui.input>`: Standard text inputs. Supports `disabled` and `error` states.
- `<x-ui.textarea>`: Multi-line text inputs.
- `<x-ui.select>`: Dropdown selection. Supports an `options` array or slot.
- `<x-ui.checkbox>`: Styled checkbox.
- `<x-ui.label>`: Standard label.
- `<x-ui.error>`: Displays validation errors.
- `<x-ui.form-group>`: A wrapper that combines label, input, and error display logically.

### Actions
- `<x-ui.button>`: Main action button. Variants: `primary`, `secondary`, `danger`, `outline`, `ghost`.
- `<x-ui.icon-button>`: Circular button for icon-only actions.

### Data & Display
- `<x-ui.badge>`: Small status indicators (`success`, `warning`, `error`, `info`, `dark`).
- `<x-ui.alert>`: Flash messages. Supports `dismissible` boolean.
- `<x-ui.card>`: Container with optional `header` and `footer` slots.
- `<x-ui.table>`: Complete table system (`x-ui.table`, `x-ui.table.th`, `x-ui.table.td`, `x-ui.table.tr`).

### Interactive
- `<x-ui.modal>`: Alpine.js modal with focus trapping and backdrop blur. Dispatch `open-modal` to trigger.

## Testing & Preview
- **Preview:** Accessible at `/ui-playground` in local development.
- **Tests:** `tests/Feature/UI/ComponentRenderingTest.php` ensures the components compile and render successfully.
