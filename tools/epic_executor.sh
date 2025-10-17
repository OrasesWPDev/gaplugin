#!/bin/bash

# Epic Executor Script
# This script is called by the /work-epic command to trigger actual execution
# It creates a trigger file that signals Claude to invoke the Task tool

EPIC_NUMBER="$1"

if [ -z "$EPIC_NUMBER" ]; then
    echo "ERROR: Epic number required"
    echo "Usage: epic_executor.sh <epic_number>"
    exit 1
fi

# Validate epic number
if ! [[ "$EPIC_NUMBER" =~ ^[0-5][0-9]?$ ]]; then
    echo "ERROR: Invalid epic number. Must be 00-05"
    exit 1
fi

# Create trigger directory if it doesn't exist
TRIGGER_DIR="/tmp/claude-epic-triggers"
mkdir -p "$TRIGGER_DIR"

# Create trigger file with timestamp
TRIGGER_FILE="$TRIGGER_DIR/epic-${EPIC_NUMBER}.trigger"
echo "EPIC: $EPIC_NUMBER" > "$TRIGGER_FILE"
echo "TIMESTAMP: $(date +%Y-%m-%d-%H:%M:%S)" >> "$TRIGGER_FILE"
echo "STATUS: READY_TO_EXECUTE" >> "$TRIGGER_FILE"

# Output clear execution signal
echo "🚀 EXECUTION TRIGGER CREATED"
echo "Epic: EPIC-${EPIC_NUMBER}"
echo "Status: READY"
echo "Trigger: $TRIGGER_FILE"
echo ""
echo "INSTRUCTION: Claude must now invoke Task tool with epic-orchestrator-executor"
echo "EPIC_NUMBER: ${EPIC_NUMBER}"

exit 0