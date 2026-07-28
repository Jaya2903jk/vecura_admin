<style>
    /* Inline Time Slot Picker for Book Appointment Modal */
    .time-slot-grid-inline {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .time-slot-btn {
        border: 1.5px solid #81c784;
        background: #f1f8e9;
        color: #1b5e20;
        border-radius: 6px;
        font-size: 12px;
        padding: 8px 6px;
        cursor: pointer;
        text-align: center;
        transition: all 0.15s ease-in-out;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        user-select: none;
    }

    .time-slot-btn:hover:not(:disabled) {
        border-color: #2e7d32;
        background: #e8f5e9;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
    }

    .time-slot-btn.selected {
        background: #0d6efd !important;
        border-color: #0b5ed7 !important;
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3) !important;
    }

    .time-slot-btn.selected .slot-status {
        color: #e0e7ff !important;
    }

    .time-slot-btn.slot-booked {
        background: #ffebee !important;
        border-color: #ef9a9a !important;
        color: #c62828 !important;
        cursor: not-allowed !important;
        opacity: 0.85;
    }

    .time-slot-btn .slot-time {
        font-size: 12px;
        font-weight: 600;
        line-height: 1.2;
    }

    .time-slot-btn .slot-status {
        font-size: 10px;
        margin-top: 2px;
        line-height: 1;
    }

    @media (max-width: 768px) {
        .time-slot-grid-inline {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
