import { AppLayout } from '@/layouts/app-layout';
import { GlassCard } from '@/components/premium/glass-card';
import { CommandK } from '@/components/premium/command-k';
import { Head } from '@inertiajs/react';
import {
    Ticket,
    TrendingUp,
    Users,
    Calendar,
    DollarSign,
    ArrowUpRight,
    ArrowDownRight,
    Search,
    Filter,
    Plus,
    ShieldCheck,
    ShieldAlert
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { DataTable } from '@/components/premium/data-table';

const stats = [
    { name: 'Total Revenue', value: '$128,430', change: '+12.5%', trending: 'up', icon: DollarSign },
    { name: 'Security Health', value: '98%', change: 'Stable', trending: 'up', icon: ShieldCheck },
    { name: 'Active Events', value: '24', change: '-2.4%', trending: 'down', icon: Calendar },
    { name: 'Unique Customers', value: '8,432', change: '+5.4%', trending: 'up', icon: Users },
];

const columns = [
    { header: 'Order ID', accessor: 'id' },
    { header: 'Customer', accessor: 'customer' },
    { header: 'Event', accessor: 'event' },
    { header: 'Amount', accessor: 'amount' },
    { header: 'Status', accessor: 'status' },
];

const recentOrders = [
    { id: 'ORD-7234', customer: 'Alex Rivera', event: 'Summer Music Festival', amount: '$150.00', status: 'Paid' },
    { id: 'ORD-7235', customer: 'Sarah Chen', event: 'Tech Conference 2026', amount: '$450.00', status: 'Paid' },
    { id: 'ORD-7236', customer: 'Michael Ross', event: 'Broadway: Lion King', amount: '$85.00', status: 'Pending' },
];

export default function AdminDashboard() {
    return (
        <AppLayout>
            <Head title="TicketFlow Dashboard" />
            <CommandK />

            <div className="flex flex-col gap-8 p-6 lg:p-10">
                {/* Header */}
                <div className="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight text-foreground">TicketFlow Overview</h1>
                        <p className="text-muted-foreground">Manage your organization's events and sales in real-time.</p>
                    </div>
                    <div className="flex items-center gap-3">
                        <Button variant="outline" className="gap-2">
                            <Filter className="size-4" /> Filters
                        </Button>
                        <Button className="gap-2 bg-primary text-primary-foreground shadow-lg shadow-primary/20">
                            <Plus className="size-4" /> Create Event
                        </Button>
                    </div>
                </div>

                {/* Stats Grid */}
                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    {stats.map((stat) => (
                        <GlassCard key={stat.name} className="flex flex-col gap-4">
                            <div className="flex items-center justify-between">
                                <div className="rounded-lg bg-primary/10 p-2 text-primary">
                                    <stat.icon className="size-6" />
                                </div>
                                <div className={cn(
                                    "flex items-center gap-1 text-sm font-medium",
                                    stat.trending === 'up' ? "text-emerald-500" : "text-rose-500"
                                )}>
                                    {stat.change}
                                    {stat.trending === 'up' ? <ArrowUpRight className="size-4" /> : <ArrowDownRight className="size-4" />}
                                </div>
                            </div>
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">{stat.name}</p>
                                <p className="text-2xl font-bold">{stat.value}</p>
                            </div>
                        </GlassCard>
                    ))}
                </div>

                {/* Main Content Area */}
                <div className="grid gap-6 lg:grid-cols-3">
                    {/* Sales Chart Mockup */}
                    <GlassCard className="lg:col-span-2">
                        <div className="mb-6 flex items-center justify-between">
                            <h3 className="text-lg font-semibold">Sales Performance</h3>
                            <select className="bg-transparent text-sm text-muted-foreground outline-none">
                                <option>Last 7 Days</option>
                                <option>Last 30 Days</option>
                                <option>Last Year</option>
                            </select>
                        </div>
                        <div className="flex h-64 items-end gap-2 px-2">
                            {[40, 70, 45, 90, 65, 80, 55, 75, 50, 85, 60, 95].map((val, i) => (
                                <div
                                    key={i}
                                    className="relative flex-1 rounded-t-lg bg-primary/20 transition-all duration-500 hover:bg-primary/40"
                                    style={{ height: `${val}%` }}
                                >
                                    <div className="absolute inset-x-0 top-0 h-1 bg-primary blur-[2px]" />
                                </div>
                            ))}
                        </div>
                        <div className="mt-4 flex justify-between px-2 text-xs text-muted-foreground">
                            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
                            <span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
                        </div>
                    </GlassCard>

                    {/* Quick Actions / Activity */}
                    <GlassCard>
                        <h3 className="mb-4 text-lg font-semibold text-foreground">Quick Actions</h3>
                        <div className="flex flex-col gap-3">
                            <Button variant="ghost" className="justify-start gap-3 border border-white/5 bg-white/5 py-6">
                                <div className="rounded-md bg-blue-500/20 p-2 text-blue-500"><Plus className="size-4" /></div>
                                <div className="text-left">
                                    <div className="text-sm font-medium">Add New Venue</div>
                                    <div className="text-xs text-muted-foreground">Setup seating maps and sections.</div>
                                </div>
                            </Button>
                            <Button variant="ghost" className="justify-start gap-3 border border-white/5 bg-white/5 py-6">
                                <div className="rounded-md bg-purple-500/20 p-2 text-purple-500"><Users className="size-4" /></div>
                                <div className="text-left">
                                    <div className="text-sm font-medium">Manage Team</div>
                                    <div className="text-xs text-muted-foreground">Invite managers and gate staff.</div>
                                </div>
                            </Button>
                            <Button variant="ghost" className="justify-start gap-3 border border-white/5 bg-white/5 py-6">
                                <div className="rounded-md bg-orange-500/20 p-2 text-orange-500"><TrendingUp className="size-4" /></div>
                                <div className="text-left">
                                    <div className="text-sm font-medium">Financial Reports</div>
                                    <div className="text-xs text-muted-foreground">Export revenue data to Excel/PDF.</div>
                                </div>
                            </Button>
                        </div>
                    </GlassCard>
                </div>

                {/* Recent Orders Table */}
                <DataTable
                    title="Real-Time Order Stream"
                    columns={columns}
                    data={recentOrders}
                />

                {/* Recent Activity */}
                <div className="grid gap-6 lg:grid-cols-2">
                    <GlassCard>
                        <div className="mb-6 flex items-center justify-between">
                            <h3 className="text-lg font-semibold flex items-center gap-2"><Activity className="size-5 text-primary" /> System Activity</h3>
                            <Button variant="link" className="text-xs">View Audit Logs</Button>
                        </div>
                        <div className="flex flex-col gap-6">
                            {[
                                { action: 'Admin refunded order #7234', time: '2 mins ago', icon: DollarSign, color: 'text-rose-500' },
                                { action: 'Gate 4: High traffic detected', time: '15 mins ago', icon: ShieldAlert, color: 'text-orange-500' },
                                { action: 'Event "Summer Festival" published', time: '45 mins ago', icon: Calendar, color: 'text-emerald-500' },
                                { action: 'New organization onboarded: TechCorp', time: '2 hours ago', icon: Users, color: 'text-blue-500' },
                            ].map((act, i) => (
                                <div key={i} className="flex gap-4 group">
                                    <div className={cn("mt-1 rounded-full bg-white/5 p-2 transition-colors group-hover:bg-white/10", act.color)}>
                                        <act.icon className="size-4" />
                                    </div>
                                    <div className="flex flex-col">
                                        <p className="text-sm font-medium text-foreground">{act.action}</p>
                                        <p className="text-xs text-muted-foreground">{act.time}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </GlassCard>

                    <GlassCard>
                        <h3 className="mb-6 text-lg font-semibold flex items-center gap-2"><TrendingUp className="size-5 text-primary" /> Growth Projection</h3>
                        <div className="flex h-48 items-center justify-center border-2 border-dashed border-white/10 rounded-xl bg-white/5">
                            <p className="text-sm text-muted-foreground italic">Interactive Projection Chart Placeholder</p>
                        </div>
                    </GlassCard>
                </div>
            </div>
        </AppLayout>
    );
}

function cn(...inputs: any[]) {
    return inputs.filter(Boolean).join(' ');
}
