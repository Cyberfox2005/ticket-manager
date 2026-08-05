import { AppLayout } from '@/layouts/app-layout';
import { GlassCard } from '@/components/premium/glass-card';
import { Head, Link } from '@inertiajs/react';
import { Plus, Search, Filter, MoreHorizontal, Calendar, MapPin } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

export default function EventIndex({ events }: any) {
    return (
        <AppLayout>
            <Head title="Events - TicketFlow" />

            <div className="flex flex-col gap-8 p-6 lg:p-10">
                <div className="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight text-foreground">Events</h1>
                        <p className="text-muted-foreground">Manage your event lineup and ticket types.</p>
                    </div>
                    <Button asChild className="gap-2 bg-primary shadow-lg shadow-primary/20">
                        <Link href="/ticketflow/admin/events/create">
                            <Plus className="size-4" /> Create Event
                        </Link>
                    </Button>
                </div>

                <GlassCard className="overflow-hidden p-0">
                    <div className="flex items-center justify-between border-b border-white/10 p-6">
                        <div className="relative w-full max-w-sm">
                            <Search className="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                            <Input placeholder="Search events..." className="bg-white/5 pl-9 border-white/10" />
                        </div>
                        <Button variant="outline" className="gap-2">
                            <Filter className="size-4" /> Filter
                        </Button>
                    </div>

                    <div className="grid gap-px bg-white/5 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                        {events.data.length > 0 ? events.data.map((event: any) => (
                            <div key={event.id} className="group relative bg-background p-6 transition-colors hover:bg-white/5">
                                <div className="mb-4 aspect-video overflow-hidden rounded-lg bg-white/5">
                                    {event.banner_path ? (
                                        <img src={event.banner_path} alt={event.title} className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                    ) : (
                                        <div className="flex h-full items-center justify-center text-muted-foreground">No Banner</div>
                                    )}
                                </div>
                                <div className="flex flex-col gap-2">
                                    <div className="flex items-center justify-between">
                                        <span className="rounded-full bg-primary/10 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-primary">
                                            {event.category?.name || 'Uncategorized'}
                                        </span>
                                        <span className={cn(
                                            "text-xs font-medium",
                                            event.status === 'published' ? "text-emerald-500" : "text-orange-500"
                                        )}>
                                            {event.status}
                                        </span>
                                    </div>
                                    <h3 className="text-lg font-bold text-foreground group-hover:text-primary transition-colors">{event.title}</h3>
                                    <div className="flex items-center gap-2 text-sm text-muted-foreground">
                                        <Calendar className="size-3" />
                                        {new Date(event.start_at).toLocaleDateString()}
                                    </div>
                                    <div className="flex items-center gap-2 text-sm text-muted-foreground">
                                        <MapPin className="size-3" />
                                        {event.venue?.name || 'No Venue'}
                                    </div>
                                </div>
                                <Button variant="ghost" size="icon" className="absolute bottom-4 right-4 text-muted-foreground opacity-0 group-hover:opacity-100 transition-opacity">
                                    <MoreHorizontal className="size-4" />
                                </Button>
                            </div>
                        )) : (
                            <div className="col-span-full py-20 text-center">
                                <p className="text-muted-foreground">No events found. Start by creating your first event!</p>
                            </div>
                        )}
                    </div>
                </GlassCard>
            </div>
        </AppLayout>
    );
}

function cn(...inputs: any[]) {
    return inputs.filter(Boolean).join(' ');
}
