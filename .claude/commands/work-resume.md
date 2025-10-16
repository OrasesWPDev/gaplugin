# /work-resume

Resume paused orchestration from where it left off.

**Usage:**
```
/work-resume [--checkpoint=<option>]
```

**Parameters:**
- `--checkpoint=<option>` - Change checkpoint strategy on resume (optional)

**What It Does:**
1. Resumes from last pause point
2. Continues with next ticket
3. Applies updated checkpoint if specified
4. Shows status and next steps

**Example Output:**
```
▶️ ORCHESTRATION RESUMED

Epic: EPIC-01
Resuming from: US-01.4 (just completed)

Next Phase:
├─ US-01.5: Plugin Initialization
├─ TT-01.2: Activator Class
└─ TT-01.3: Placeholder Classes

Checkpoint Strategy: --checkpoint=US
Next Checkpoint: After US-01.5

Continuing execution...
🔄 Starting US-01.5...
```

**See also:**
- [work-pause](work-pause.md)
- [work-status](work-status.md)
