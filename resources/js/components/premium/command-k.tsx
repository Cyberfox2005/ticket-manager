import React, { useState, useEffect } from 'react';
import { Search, Command } from 'lucide-react';
import { GlassCard } from './glass-card';

export function CommandK() {
    const [open, setOpen] = useState(false);

    useEffect(() => {
        const down = (e: KeyboardEvent) => {
            if (e.key === 'k' && (e.metaKey || e.ctrlKey)) {
                e.preventDefault();
                setOpen((open) => !open);
            }
        };
        document.addEventListener('keydown', down);
        return () => document.removeEventListener('keydown', down);
    }, []);

    if (!open) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-start justify-center pt-[20vh] bg-black/40 backdrop-blur-sm animate-in fade-in duration-200">
            <GlassCard className="w-full max-w-2xl p-0 overflow-hidden shadow-2xl border-white/20">
                <div className="flex items-center px-4 border-b border-white/10">
                    <Search className="size-5 text-muted-foreground" />
                    <input
                        autoFocus
                        placeholder="Search events, orders, or security logs... (esc to close)"
                        className="w-full p-4 bg-transparent outline-none text-foreground placeholder:text-muted-foreground"
                    />
                    <kbd className="hidden sm:inline-flex h-5 select-none items-center gap-1 rounded border border-white/20 bg-white/5 px-1.5 font-mono text-[10px] font-medium text-muted-foreground opacity-100">
                        ESC
                    </kbd>
                </div>
                <div className="max-h-[300px] overflow-y-auto p-2">
                    <div className="px-2 py-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Recent Activity</div>
                    {['Refunded Order #7234', 'Created Summer Music Festival', 'New Organization: TechCorp'].map((item, i) => (
                        <div key={i} className="flex items-center px-3 py-2 rounded-lg hover:bg-white/5 cursor-pointer transition-colors group">
                            <Command className="size-4 mr-3 text-muted-foreground group-hover:text-primary" />
                            <span className="text-sm">{item}</span>
                        </div>
                    ))}
                </div>
            </GlassCard>
        </div>
    );
}
