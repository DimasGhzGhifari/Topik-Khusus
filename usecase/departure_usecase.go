package usecase

import (
	"context"
	"redis-car-departure-system/domain"
	"redis-car-departure-system/repository"
)

type DepartureUsecase interface {
	ProcessNextDeparture(ctx context.Context) (*domain.Departure, error)
	GetQueueLength(ctx context.Context) (int64, error)
	GetDepartureHistory(ctx context.Context) ([]*domain.Departure, error)
}

type departureUsecase struct {
	departureRepo repository.DepartureRepository
	vehicleRepo   repository.VehicleRepository
}

func NewDepartureUsecase(departureRepo repository.DepartureRepository, vehicleRepo repository.VehicleRepository) DepartureUsecase {
	return &departureUsecase{
		departureRepo: departureRepo,
		vehicleRepo:   vehicleRepo,
	}
}

func (u *departureUsecase) ProcessNextDeparture(ctx context.Context) (*domain.Departure, error) {
	departure, err := u.departureRepo.ProcessDeparture(ctx)
	if err != nil {
		return nil, err
	}

	// Update vehicle status to departed
	err = u.vehicleRepo.UpdateStatus(ctx, departure.VehicleID, "sudah_berangkat")
	return departure, err
}

func (u *departureUsecase) GetQueueLength(ctx context.Context) (int64, error) {
	return u.departureRepo.GetQueueLength(ctx)
}

func (u *departureUsecase) GetDepartureHistory(ctx context.Context) ([]*domain.Departure, error) {
	return u.departureRepo.GetDepartureHistory(ctx)
}