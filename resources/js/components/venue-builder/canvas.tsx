import React, { useState, useRef, useEffect } from 'react';
import { Stage, Layer, Rect, Circle, Group, Text, Line } from 'react-konva';
import { GlassCard } from '@/components/premium/glass-card';

interface Seat {
    id: string;
    x: number;
    y: number;
    row: string;
    num: string;
    status: string;
}

interface Section {
    id: string;
    name: string;
    seats: Seat[];
}

export function VenueCanvas() {
    const [sections, setSections] = useState<Section[]>([]);
    const [scale, setScale] = useState(1);
    const [position, setPosition] = useState({ x: 0, y: 0 });
    const stageRef = useRef<any>(null);

    // Zoom on wheel
    const handleWheel = (e: any) => {
        e.evt.preventDefault();
        const scaleBy = 1.1;
        const stage = stageRef.current;
        const oldScale = stage.scaleX();

        const mousePointTo = {
            x: stage.getPointerPosition().x / oldScale - stage.x() / oldScale,
            y: stage.getPointerPosition().y / oldScale - stage.y() / oldScale,
        };

        const newScale = e.evt.deltaY < 0 ? oldScale * scaleBy : oldScale / scaleBy;

        setScale(newScale);
        setPosition({
            x: -(mousePointTo.x - stage.getPointerPosition().x / newScale) * newScale,
            y: -(mousePointTo.y - stage.getPointerPosition().y / newScale) * newScale,
        });
    };

    return (
        <div className="relative h-[800px] w-full overflow-hidden rounded-3xl border border-white/10 bg-neutral-950 shadow-2xl">
            <Stage
                width={window.innerWidth}
                height={800}
                onWheel={handleWheel}
                scaleX={scale}
                scaleY={scale}
                x={position.x}
                y={position.y}
                draggable
                ref={stageRef}
            >
                <Layer>
                    {sections.map((section) => (
                        <Group key={section.id}>
                            {section.seats.map((seat) => (
                                <Circle
                                    key={seat.id}
                                    x={seat.x}
                                    y={seat.y}
                                    radius={8}
                                    fill={seat.status === 'vip' ? '#FFD700' : '#3b82f6'}
                                    stroke="#fff"
                                    strokeWidth={1}
                                    draggable
                                    onDragEnd={(e) => {
                                        // Update seat position logic
                                    }}
                                />
                            ))}
                        </Group>
                    ))}

                    {/* Grid Background (Simulated) */}
                    <Line
                        points={[-5000, 0, 5000, 0]}
                        stroke="rgba(255,255,255,0.05)"
                        strokeWidth={1}
                    />
                </Layer>
            </Stage>

            {/* Overlay Controls */}
            <div className="absolute bottom-6 left-6 flex gap-2">
                <GlassCard className="flex items-center gap-4 py-2 px-4">
                    <button onClick={() => setScale(s => s * 1.2)} className="text-white hover:text-primary">+</button>
                    <span className="text-xs font-mono text-muted-foreground">{Math.round(scale * 100)}%</span>
                    <button onClick={() => setScale(s => s / 1.2)} className="text-white hover:text-primary">-</button>
                </GlassCard>
            </div>
        </div>
    );
}
