# Dispatch — Block Implementation Queue

## Workflow

Process the checklist below **one item at a time**, top to bottom:

1. Implement the next unchecked block from the **Queue** section using the `/acf-block-from-figma` skill against the linked Figma node.
2. After the block is fully implemented (PHP + SCSS + JSON registered, builds clean), mark the item as checked (`[x]`) in this file.
3. Run `/usage` to read the current context window usage.
4. **Decision gate:**
   - If usage **< 60%** → proceed to the next unchecked item in the queue and repeat from step 1.
   - If usage **≥ 60%** → **stop**. Reply with the current usage percentage and wait for the user to prompt before continuing.
5. When all items are checked, report completion and stop.

## Rules

- Do not skip items. Process strictly in order.
- Do not batch multiple blocks in a single iteration — implement, check off, then re-evaluate usage.
- Only modify the checkbox state after the block is verifiably complete (files saved, no build errors).
- The usage check is a hard gate. Never continue past 60% without an explicit user prompt.

## Queue

- [ ] **Product Showcase** — Implement this design from Figma: https://www.figma.com/design/zlrzFkDYAS9kXKv8abehhb/OneOne-Website?node-id=638-3606&m=dev
- [ ] **Text Banner** — Implement this design from Figma: https://www.figma.com/design/zlrzFkDYAS9kXKv8abehhb/OneOne-Website?node-id=655-4115&m=dev
