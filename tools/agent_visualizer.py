#!/usr/bin/env python3
"""
Agent Visualizer Dashboard - Real-time terminal UI for multi-agent execution

Displays live status of:
- Epic progress (all 6 epics with progress bars)
- Agent status (7 agents with current tasks)
- Recent activity (commits, completions, errors)
- System health metrics (success rate, ETA, quality gates)
"""

import time
import sys
import os
from datetime import datetime
from pathlib import Path

from rich.console import Console
from rich.layout import Layout
from rich.live import Live
from rich.panel import Panel
from rich.table import Table
from rich.text import Text

# Add parent directory to path for imports
sys.path.insert(0, str(Path(__file__).parent))

from agent_data_collector import AgentDataCollector


class AgentDashboard:
    """Real-time terminal dashboard for agent execution monitoring."""

    def __init__(self, repo_root: str = "."):
        self.console = Console()
        self.collector = AgentDataCollector(repo_root)
        self.start_time = datetime.now()
        self.refresh_rate = 5  # Default refresh rate in seconds

    def create_layout(self) -> Layout:
        """Create the main dashboard layout."""
        layout = Layout()
        layout.split_column(
            Layout(name="header", size=3),
            Layout(name="main"),
            Layout(name="footer", size=2),
        )

        layout["main"].split_row(
            Layout(name="left_panel"),
            Layout(name="right_panel"),
        )

        layout["left_panel"].split_column(
            Layout(name="epics"),
            Layout(name="agents"),
        )

        layout["right_panel"].split_column(
            Layout(name="metrics"),
            Layout(name="activity"),
        )

        return layout

    def get_header(self) -> Panel:
        """Get the header panel."""
        title = Text("🤖 AGENT EXECUTION DASHBOARD", style="bold cyan")
        timestamp = Text(
            f"Updated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}",
            style="dim white",
        )

        header_text = Text.assemble(title, "\n", timestamp)
        return Panel(header_text, style="blue", expand=True)

    def get_epic_progress(self, data: dict) -> Panel:
        """Get epic progress panel."""
        epics = data.get("epics", {})

        if not epics:
            return Panel("No epic data available", title="📊 Epic Progress")

        # Create progress visualization
        lines = []
        for epic_key in sorted(epics.keys()):
            epic = epics[epic_key]
            num = epic_key.replace("EPIC-", "")
            completed = epic["completed"]
            total = epic["total"]
            percentage = epic["percentage"]
            status = epic["status"]

            # Create progress bar
            bar_length = 20
            filled = int(bar_length * percentage / 100)
            bar = "█" * filled + "░" * (bar_length - filled)

            # Create line
            line = f"{epic_key} {bar} {percentage:5.1f}% ({completed}/{total}) {status}"
            lines.append(line)

        content = "\n".join(lines)
        return Panel(
            content,
            title="📊 Epic Progress",
            border_style="blue",
            padding=(1, 2),
        )

    def get_agent_status(self, data: dict) -> Panel:
        """Get agent status grid."""
        agents = data.get("agents", {})

        if not agents:
            return Panel("No agent data available", title="🤖 Agent Status")

        # Create table
        table = Table(show_header=True, header_style="bold cyan", padding=(0, 1))
        table.add_column("Agent", style="cyan")
        table.add_column("Status", style="green")
        table.add_column("Current Task", style="yellow")
        table.add_column("Progress", style="magenta")

        for agent_id in sorted(agents.keys()):
            agent = agents[agent_id]
            status = agent["status"]
            task = agent["current_task"][:40]  # Truncate to 40 chars
            progress = f"{agent['progress']}%"

            table.add_row(agent_id, status, task, progress)

        return Panel(
            table,
            title="🤖 Agent Status",
            border_style="green",
            padding=(1, 1),
        )

    def get_system_metrics(self, data: dict) -> Panel:
        """Get system metrics panel."""
        metrics = data.get("system_metrics", {})

        lines = [
            f"Total Tasks:        {metrics.get('total_tasks', 0)}/{metrics.get('total_tasks', 0)}",
            f"Completed:          {metrics.get('completed_tasks', 0)}/{metrics.get('total_tasks', 0)}",
            f"Progress:           {metrics.get('percentage', 0):.1f}%",
            f"Quality Gates:      {metrics.get('quality_gates_passed', 0)}/{metrics.get('quality_gates_total', 0)} ✅",
            f"Success Rate:       {metrics.get('success_rate', 0):.1f}%",
            f"ETA:                {metrics.get('estimated_remaining', 'N/A')}",
            f"Active Agents:      {metrics.get('active_agents', 0)}/{metrics.get('total_agents', 0)}",
        ]

        content = "\n".join(lines)
        return Panel(
            content,
            title="📈 System Metrics",
            border_style="magenta",
            padding=(1, 2),
        )

    def get_recent_activity(self, data: dict) -> Panel:
        """Get recent activity feed."""
        activity = data.get("recent_activity", [])

        lines = []
        for item in activity[-8:]:  # Show last 8 items
            emoji = item.get("emoji", "📝")
            message = item.get("message", "Unknown")[:50]  # Truncate
            timestamp = item.get("timestamp", "")

            line = f"{emoji} {timestamp} {message}"
            lines.append(line)

        if not lines:
            lines.append("No recent activity")

        content = "\n".join(lines)
        return Panel(
            content,
            title="📜 Recent Activity",
            border_style="yellow",
            padding=(1, 1),
        )

    def get_footer(self) -> Panel:
        """Get the footer panel."""
        uptime = datetime.now() - self.start_time
        uptime_str = f"{int(uptime.total_seconds() / 60)} min"

        footer_text = (
            f"⏱ Uptime: {uptime_str} | 🔄 Live Updates Every {self.refresh_rate}s | Press Ctrl+C to Exit"
        )
        return Panel(footer_text, style="dim white")

    def display_dashboard(self, refresh_rate: int = 5):
        """Display the live dashboard."""
        self.refresh_rate = refresh_rate  # Store for use in get_footer()
        layout = self.create_layout()

        try:
            with Live(
                self.generate_dashboard(layout),
                refresh_per_second=4,  # Increase for smoother visual updates
                console=self.console,
            ) as live:
                while True:
                    live.update(self.generate_dashboard(layout))
                    time.sleep(refresh_rate)
        except KeyboardInterrupt:
            self.console.print(
                "\n[yellow]Dashboard stopped by user[/yellow]",
                justify="center",
            )

    def generate_dashboard(self, layout: Layout) -> Layout:
        """Generate current dashboard content."""
        data = self.collector.collect_all_data()

        # Update header
        layout["header"].update(self.get_header())

        # Update left panel (epics and agents)
        layout["epics"].update(self.get_epic_progress(data))
        layout["agents"].update(self.get_agent_status(data))

        # Update right panel (metrics and activity)
        layout["metrics"].update(self.get_system_metrics(data))
        layout["activity"].update(self.get_recent_activity(data))

        # Update footer
        layout["footer"].update(self.get_footer())

        return layout

    def run(self, refresh_rate: int = 5, once: bool = False):
        """Run the dashboard."""
        self.refresh_rate = refresh_rate  # Store for use in get_footer()

        if once:
            # Single update mode
            layout = self.create_layout()
            data = self.collector.collect_all_data()

            layout["header"].update(self.get_header())
            layout["epics"].update(self.get_epic_progress(data))
            layout["agents"].update(self.get_agent_status(data))
            layout["metrics"].update(self.get_system_metrics(data))
            layout["activity"].update(self.get_recent_activity(data))
            layout["footer"].update(self.get_footer())

            self.console.print(layout)
        else:
            # Live mode
            self.display_dashboard(refresh_rate)


def main():
    """Main entry point."""
    import argparse

    parser = argparse.ArgumentParser(description="Agent Execution Dashboard")
    parser.add_argument(
        "--refresh-rate",
        type=int,
        default=5,
        help="Refresh rate in seconds (default: 5)",
    )
    parser.add_argument(
        "--once",
        action="store_true",
        help="Show dashboard once and exit",
    )
    parser.add_argument(
        "--repo-root",
        type=str,
        default=".",
        help="Repository root directory (default: current directory)",
    )

    args = parser.parse_args()

    # Change to repo root if specified
    if args.repo_root != ".":
        os.chdir(args.repo_root)

    dashboard = AgentDashboard(repo_root=".")
    dashboard.run(refresh_rate=args.refresh_rate, once=args.once)


if __name__ == "__main__":
    main()
